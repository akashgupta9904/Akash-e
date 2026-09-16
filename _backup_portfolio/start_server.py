import http.server
import socketserver
import os
import sys

# Force utf-8 stdout if possible
if sys.platform == "win32":
    sys.stdout.reconfigure(encoding='utf-8', errors='replace')

PORT = 3000
DIRECTORY = os.path.dirname(os.path.abspath(__file__))

class Handler(http.server.SimpleHTTPRequestHandler):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, directory=DIRECTORY, **kwargs)

if __name__ == "__main__":
    with socketserver.TCPServer(("", PORT), Handler) as httpd:
        url = f"http://localhost:{PORT}"
        print("=" * 60)
        print("Ashu's Official Website is Running Locally! [LGBTQ+ Pride]")
        print(f"Open your browser at: {url}")
        print(f"Directory: {DIRECTORY}")
        print("=" * 60)
        try:
            httpd.serve_forever()
        except KeyboardInterrupt:
            print("\nServer stopped.")
