using System;
using System.Diagnostics;
using System.Drawing;
using System.IO;
using System.Threading.Tasks;
using System.Windows.Forms;
using Microsoft.Web.WebView2.Core;
using Microsoft.Web.WebView2.WinForms;

namespace AshuGayProfile
{
    public class MainForm : Form
    {
        private readonly WebView2 webView;
        private bool isFullScreen = false;
        private FormWindowState previousWindowState = FormWindowState.Normal;
        private FormBorderStyle previousBorderStyle = FormBorderStyle.Sizable;

        public MainForm()
        {
            InitializeWindow();

            webView = new WebView2
            {
                Dock = DockStyle.Fill,
                DefaultBackgroundColor = Color.FromArgb(10, 11, 16)
            };

            this.Controls.Add(webView);
            this.KeyPreview = true;
            this.KeyDown += MainForm_KeyDown;

            this.Load += async (s, e) => await InitializeWebViewAsync();
        }

        private void InitializeWindow()
        {
            this.Text = "Ashu Gay Profile - Official Showcase \ud83c\udff3\ufe0f\u200d\ud83c\udf08 (21 & Proud)";
            this.Width = 1280;
            this.Height = 820;
            this.MinimumSize = new Size(900, 600);
            this.StartPosition = FormStartPosition.CenterScreen;
            this.BackColor = Color.FromArgb(10, 11, 16);
            
            // Set modern visual styling
            this.DoubleBuffered = true;
        }

        private async Task InitializeWebViewAsync()
        {
            try
            {
                // Isolate user data under LocalAppData
                string userDataFolder = Path.Combine(
                    Environment.GetFolderPath(Environment.SpecialFolder.LocalApplicationData),
                    "AshuGayProfile",
                    "WebView2Data"
                );
                Directory.CreateDirectory(userDataFolder);

                var environment = await CoreWebView2Environment.CreateAsync(null, userDataFolder);
                await webView.EnsureCoreWebView2Async(environment);

                // Configure WebView2 settings
                webView.CoreWebView2.Settings.IsStatusBarEnabled = false;
                webView.CoreWebView2.Settings.AreDevToolsEnabled = true;
                webView.CoreWebView2.Settings.IsZoomControlEnabled = true;
                webView.CoreWebView2.Settings.IsBuiltInErrorPageEnabled = true;

                // Map local wwwroot to virtual secure host
                string baseDir = AppDomain.CurrentDomain.BaseDirectory;
                string wwwrootDir = Path.Combine(baseDir, "wwwroot");

                if (!Directory.Exists(wwwrootDir))
                {
                    // Fallback to parent directory if running directly from bin/Debug
                    string parentWwwroot = Path.Combine(baseDir, "..", "..", "..", "wwwroot");
                    if (Directory.Exists(parentWwwroot))
                    {
                        wwwrootDir = Path.GetFullPath(parentWwwroot);
                    }
                }

                if (Directory.Exists(wwwrootDir))
                {
                    webView.CoreWebView2.SetVirtualHostNameToFolderMapping(
                        "appassets.local",
                        wwwrootDir,
                        CoreWebView2HostResourceAccessKind.Allow
                    );
                    webView.CoreWebView2.Navigate("https://appassets.local/index.html");
                }
                else
                {
                    // If directory is missing, show fallback message
                    webView.CoreWebView2.NavigateToString(@"
                        <html>
                        <body style='background:#0a0b10;color:white;font-family:sans-serif;text-align:center;padding:50px;'>
                            <h1>🏳️‍🌈 Ashu Gay Profile</h1>
                            <p>Assets folder (wwwroot) not found at: " + wwwrootDir + @"</p>
                        </body>
                        </html>
                    ");
                }

                // Handle external links -> open in default system browser
                webView.CoreWebView2.NewWindowRequested += CoreWebView2_NewWindowRequested;
                
                // Allow title updates from webpage
                webView.CoreWebView2.DocumentTitleChanged += (s, e) =>
                {
                    if (!string.IsNullOrWhiteSpace(webView.CoreWebView2.DocumentTitle))
                    {
                        this.Text = webView.CoreWebView2.DocumentTitle;
                    }
                };
            }
            catch (Exception ex)
            {
                MessageBox.Show(
                    "WebView2 initialization failed:\n" + ex.Message + 
                    "\n\nPlease ensure Microsoft Edge WebView2 Runtime is installed.",
                    "Ashu Gay Profile - Error",
                    MessageBoxButtons.OK,
                    MessageBoxIcon.Error
                );
            }
        }

        private void CoreWebView2_NewWindowRequested(object? sender, CoreWebView2NewWindowRequestedEventArgs e)
        {
            if (!string.IsNullOrEmpty(e.Uri) && !e.Uri.StartsWith("https://appassets.local", StringComparison.OrdinalIgnoreCase))
            {
                e.Handled = true;
                try
                {
                    Process.Start(new ProcessStartInfo(e.Uri) { UseShellExecute = true });
                }
                catch (Exception ex)
                {
                    Debug.WriteLine("Failed to open external link: " + ex.Message);
                }
            }
        }

        private void MainForm_KeyDown(object? sender, KeyEventArgs e)
        {
            if (e.KeyCode == Keys.F11)
            {
                ToggleFullScreen();
                e.Handled = true;
            }
            else if (e.KeyCode == Keys.F5 || (e.Control && e.KeyCode == Keys.R))
            {
                webView.Reload();
                e.Handled = true;
            }
            else if (e.Control && (e.KeyCode == Keys.Oemplus || e.KeyCode == Keys.Add))
            {
                webView.ZoomFactor = Math.Min(webView.ZoomFactor + 0.1, 3.0);
                e.Handled = true;
            }
            else if (e.Control && (e.KeyCode == Keys.OemMinus || e.KeyCode == Keys.Subtract))
            {
                webView.ZoomFactor = Math.Max(webView.ZoomFactor - 0.1, 0.5);
                e.Handled = true;
            }
            else if (e.Control && (e.KeyCode == Keys.D0 || e.KeyCode == Keys.NumPad0))
            {
                webView.ZoomFactor = 1.0;
                e.Handled = true;
            }
            else if (e.KeyCode == Keys.Escape && isFullScreen)
            {
                ToggleFullScreen();
                e.Handled = true;
            }
        }

        private void ToggleFullScreen()
        {
            if (!isFullScreen)
            {
                previousWindowState = this.WindowState;
                previousBorderStyle = this.FormBorderStyle;
                this.FormBorderStyle = FormBorderStyle.None;
                this.WindowState = FormWindowState.Maximized;
                isFullScreen = true;
            }
            else
            {
                this.FormBorderStyle = previousBorderStyle;
                this.WindowState = previousWindowState == FormWindowState.Maximized ? FormWindowState.Normal : previousWindowState;
                isFullScreen = false;
            }
        }
    }
}
