import os
import requests
import urllib.request

# ===== CONFIG =====
ROOT_FOLDER = r"./img"   # root folder to scan
BASE_URL = "https://adelux1.foxcreation.net/assets/images/"  # your custom base URL
IMAGE_EXTENSIONS = (".jpg", ".jpeg", ".png", ".webp")
TIMEOUT = 15
# ==================

# Folder where THIS script is located
ROOT_FOLDER = os.path.dirname(os.path.abspath(__file__))

for root, dirs, files in os.walk(ROOT_FOLDER):
    for file in files:
        if file.lower().endswith(IMAGE_EXTENSIONS):

            # Full local path
            local_path = os.path.join(root, file)

            # Relative path from the ROOT_FOLDER
            relative_path = os.path.relpath(local_path, ROOT_FOLDER)

            # Convert Windows backslashes to forward slashes for URL
            relative_url_path = relative_path.replace("\\", "/")

            # Full URL
            url = BASE_URL + relative_url_path

            try:
                # Download and overwrite
                urllib.request.urlretrieve(url, local_path)
                print(f"🔄 Overwritten: {local_path}")
            except Exception as e:
                print(f"❌ Failed: {url} | {e}")

print("🎯 All subfolders processed.")