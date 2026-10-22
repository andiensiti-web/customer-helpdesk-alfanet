import pandas as pd
import joblib

from pathlib import Path
from sklearn.model_selection import train_test_split
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import confusion_matrix, accuracy_score, classification_report


# ============================================================
# 1. MEMBACA DATASET
# ============================================================

BASE_DIR = Path(__file__).resolve().parent
DATASET = BASE_DIR / "dataset.csv"
MODEL = BASE_DIR / "model.pkl"

df = pd.read_csv(DATASET)

df["teks"] = (
    df["jenis_gangguan"].astype(str)
    + " "
    + df["deskripsi"].astype(str)
)

X = df["teks"]
y = df["prioritas"]


# ============================================================
# 2. MEMBAGI DATA LATIH DAN DATA UJI
# ============================================================

X_train, X_test, y_train, y_test = train_test_split(
    X,
    y,
    test_size=0.2,
    random_state=42,
    stratify=y
)


# ============================================================
# 3. EKSTRAKSI FITUR TF-IDF
# ============================================================

vectorizer = TfidfVectorizer(
    lowercase=True,
    ngram_range=(1, 2)
)

X_train_tfidf = vectorizer.fit_transform(X_train)
X_test_tfidf = vectorizer.transform(X_test)


# ============================================================
# 4. PELATIHAN MODEL LOGISTIC REGRESSION
# ============================================================

model = LogisticRegression(
    max_iter=1000,
    random_state=42
)

model.fit(X_train_tfidf, y_train)


# ============================================================
# 5. PREDIKSI DATA UJI
# ============================================================

y_pred = model.predict(X_test_tfidf)

labels = ["rendah", "sedang", "tinggi"]

cm = confusion_matrix(
    y_test,
    y_pred,
    labels=labels
)


# ============================================================
# 6. EVALUASI MODEL
# ============================================================

accuracy = accuracy_score(y_test, y_pred)

report = classification_report(
    y_test,
    y_pred,
    labels=labels,
    target_names=labels,
    zero_division=0,
    digits=2
)


# ============================================================
# 7. MENAMPILKAN HASIL EVALUASI
# ============================================================

print("\n" + "=" * 35)
print("HASIL EVALUASI MODEL")
print("=" * 35)

print(f"\nAccuracy : {accuracy * 100:.2f}%")

print("\nClassification Report:")
print(report)

print("Confusion Matrix:")
print(cm)


# ============================================================
# 8. MENYIMPAN MODEL
# ============================================================

model_data = {
    "vectorizer": vectorizer,
    "model": model
}

joblib.dump(model_data, MODEL)

print(f"\nModel berhasil disimpan sebagai: {MODEL.name}")