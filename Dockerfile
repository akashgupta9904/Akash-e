# Production Dockerfile for Nexus E-Commerce Platform
FROM node:24-alpine

WORKDIR /app

# Install dependencies
COPY package*.json ./
RUN npm ci --omit=dev

# Copy application source code
COPY . .

# Create directory for SQLite storage and uploads
RUN mkdir -p data public/uploads

# Expose server port
EXPOSE 3000

ENV NODE_ENV=production
ENV PORT=3000

CMD ["node", "server.js"]
