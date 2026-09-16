const express = require('express');
const router = express.Router();
const crypto = require('crypto');
const { query, getOne, execute } = require('../config/db');

const RAZORPAY_KEY_ID = process.env.RAZORPAY_KEY_ID || '';
const RAZORPAY_KEY_SECRET = process.env.RAZORPAY_KEY_SECRET || '';
const STRIPE_SECRET_KEY = process.env.STRIPE_SECRET_KEY || '';

// Create payment gateway order/intent
router.post('/create-gateway-order', async (req, res) => {
  try {
    const { orderNumber, gateway = 'test_gateway' } = req.body;
    const order = getOne('SELECT * FROM orders WHERE order_number = ?', [orderNumber]);
    if (!order) {
      return res.status(404).json({ success: false, message: 'Order not found.' });
    }

    if (order.payment_status === 'paid') {
      return res.status(400).json({ success: false, message: 'This order has already been paid.' });
    }

    // 1. Razorpay
    if (gateway === 'razorpay') {
      if (RAZORPAY_KEY_ID && RAZORPAY_KEY_SECRET) {
        // Real Razorpay API call
        const auth = Buffer.from(`${RAZORPAY_KEY_ID}:${RAZORPAY_KEY_SECRET}`).toString('base64');
        const response = await fetch('https://api.razorpay.com/v1/orders', {
          method: 'POST',
          headers: {
            'Authorization': `Basic ${auth}`,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            amount: Math.round(order.total_amount * 100), // paise
            currency: 'INR',
            receipt: order.order_number,
            notes: { orderNumber: order.order_number }
          })
        });
        const rzOrder = await response.json();
        return res.json({
          success: true,
          gateway: 'razorpay',
          keyId: RAZORPAY_KEY_ID,
          gatewayOrderId: rzOrder.id,
          amount: rzOrder.amount,
          currency: rzOrder.currency
        });
      } else {
        // Mock fallback for test environment
        const mockOrderId = `order_rzp_mock_${Date.now()}`;
        return res.json({
          success: true,
          gateway: 'razorpay',
          keyId: 'rzp_test_mock_key',
          gatewayOrderId: mockOrderId,
          amount: Math.round(order.total_amount * 100),
          currency: 'INR',
          isMock: true
        });
      }
    }

    // 2. Stripe
    if (gateway === 'stripe') {
      if (STRIPE_SECRET_KEY) {
        const params = new URLSearchParams();
        params.append('amount', Math.round(order.total_amount * 100).toString());
        params.append('currency', 'inr');
        params.append('metadata[orderNumber]', order.order_number);

        const response = await fetch('https://api.stripe.com/v1/payment_intents', {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${STRIPE_SECRET_KEY}`,
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: params.toString()
        });
        const pi = await response.json();
        return res.json({
          success: true,
          gateway: 'stripe',
          clientSecret: pi.client_secret,
          paymentIntentId: pi.id
        });
      } else {
        return res.json({
          success: true,
          gateway: 'stripe',
          clientSecret: `pi_mock_${Date.now()}_secret_test`,
          isMock: true
        });
      }
    }

    // 3. Test Gateway Simulator
    return res.json({
      success: true,
      gateway: 'test_gateway',
      gatewayOrderId: `SIM_ORDER_${Date.now()}`,
      amount: order.total_amount,
      currency: 'INR'
    });
  } catch (err) {
    console.error('Create gateway order error:', err);
    res.status(500).json({ success: false, message: 'Failed to initiate payment gateway.' });
  }
});

// Verify Payment & Update Order
router.post('/verify', (req, res) => {
  try {
    const {
      orderNumber,
      gateway,
      razorpay_order_id,
      razorpay_payment_id,
      razorpay_signature,
      stripe_payment_intent_id,
      transaction_id,
      status = 'success'
    } = req.body;

    const order = getOne('SELECT * FROM orders WHERE order_number = ?', [orderNumber]);
    if (!order) {
      return res.status(404).json({ success: false, message: 'Order not found.' });
    }

    // Duplicate callback check (Section 16 requirement)
    if (order.payment_status === 'paid') {
      return res.json({
        success: true,
        message: 'Payment already verified and processed for this order.',
        alreadyProcessed: true,
        orderNumber: order.order_number
      });
    }

    let isVerified = false;
    let actualTxId = transaction_id || razorpay_payment_id || stripe_payment_intent_id || `TX_${Date.now()}`;

    // Razorpay signature verification
    if (gateway === 'razorpay' && RAZORPAY_KEY_SECRET && razorpay_order_id && razorpay_payment_id && razorpay_signature) {
      const generatedSignature = crypto
        .createHmac('sha256', RAZORPAY_KEY_SECRET)
        .update(`${razorpay_order_id}|${razorpay_payment_id}`)
        .digest('hex');

      if (generatedSignature === razorpay_signature) {
        isVerified = true;
      } else {
        return res.status(400).json({ success: false, message: 'Invalid payment signature.' });
      }
    } else {
      // In simulator or test mode with valid status
      if (status === 'success' || status === 'paid') {
        isVerified = true;
      }
    }

    if (isVerified) {
      // Update order status
      execute(
        `UPDATE orders SET payment_status = 'paid', order_status = 'processing' WHERE id = ?`,
        [order.id]
      );

      // Check for duplicate payment transaction record
      const existingPayment = getOne('SELECT id FROM payments WHERE transaction_id = ?', [actualTxId]);
      if (!existingPayment) {
        execute(`
          INSERT INTO payments (order_id, gateway, transaction_id, amount, currency, status, signature, raw_payload)
          VALUES (?, ?, ?, ?, 'INR', 'success', ?, ?)
        `, [
          order.id,
          gateway || 'test_gateway',
          actualTxId,
          order.total_amount,
          razorpay_signature || 'verified_ok',
          JSON.stringify(req.body)
        ]);
      }

      return res.json({
        success: true,
        message: 'Payment verified successfully! Order is now confirmed.',
        orderNumber: order.order_number,
        transactionId: actualTxId
      });
    } else {
      execute(`UPDATE orders SET payment_status = 'failed' WHERE id = ?`, [order.id]);
      return res.status(400).json({ success: false, message: 'Payment verification failed.' });
    }
  } catch (err) {
    console.error('Payment verification error:', err);
    res.status(500).json({ success: false, message: 'Failed to verify payment.' });
  }
});

// Webhook endpoint for gateways
router.post('/webhook', express.raw({ type: 'application/json' }), (req, res) => {
  try {
    const signature = req.headers['x-razorpay-signature'] || req.headers['stripe-signature'];
    console.log('Payment webhook received with signature:', signature);
    // Return 200 acknowledge
    res.status(200).json({ received: true });
  } catch (err) {
    res.status(400).send(`Webhook Error: ${err.message}`);
  }
});

module.exports = router;
