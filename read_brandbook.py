#!/usr/bin/env python3
import pdfplumber
import re

pdf_path = r"C:\Users\Willem\Consul Infra\Consul Infra Shared - Documenten\Marketing\Logo''s en Huisstijlen\Brand Book Consul\Brand Book Consul.pdf"

print("="*80)
print("READING CONSUL INFRA BRAND BOOK")
print("="*80)
print()

try:
    with pdfplumber.open(pdf_path) as pdf:
        print(f"Total pages: {len(pdf.pages)}\n")

        # Extract all text
        for i, page in enumerate(pdf.pages):
            text = page.extract_text()
            if text:
                print(f"\n{'='*80}")
                print(f"PAGE {i+1}")
                print(f"{'='*80}\n")
                print(text)

except Exception as e:
    print(f"Error: {e}")
    import traceback
    traceback.print_exc()
