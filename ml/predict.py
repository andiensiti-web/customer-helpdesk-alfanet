import sys
import joblib
from pathlib import Path

BASE_DIR = Path(__file__).resolve().parent

data = joblib.load(BASE_DIR / "model.pkl")

vectorizer = data["vectorizer"]
model = data["model"]

if len(sys.argv) < 3:
    print("Input tidak lengkap.")
    sys.exit(1)

jenis_gangguan = sys.argv[1]
deskripsi = sys.argv[2]

teks = jenis_gangguan + " " + deskripsi

teks_tfidf = vectorizer.transform([teks])

prediksi = model.predict(teks_tfidf)[0]

print(prediksi)