import pdfplumber

pdf_path = r"C:\Users\Willem\Consul Infra\Consul Infra Shared - Documenten\Marketing\Logo's en Huisstijlen\Brand Book Consul\Brand Book Consul.pdf"

print("="*80)
print("READING CONSUL INFRA BRAND BOOK")
print("="*80)
print()

try:
    with pdfplumber.open(pdf_path) as pdf:
        print(f"Total pages: {len(pdf.pages)}\n")
        for i, page in enumerate(pdf.pages):
            text = page.extract_text()
            if text:
                print(f"\nPAGE {i+1}:\n")
                print(text[:1500])
                if len(text) > 1500:
                    print("\n... (truncated)")
except Exception as e:
    print(f"Error: {e}")
