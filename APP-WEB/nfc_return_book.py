import serial
import mysql.connector
from mysql.connector import Error

# Ubah port ke COM6 sesuai dengan port Arduino
ser = serial.Serial('COM6', 115200, timeout=1)

# Fungsi untuk menghapus data dari database MySQL
def delete_from_database(uid):
    try:
        # Koneksi ke database MySQL
        connection = mysql.connector.connect(
            host='localhost',
            database='gebooks',
            user='root',
            password=''  # Ganti dengan password MySQL kamu (default kosong di XAMPP)
        )

        if connection.is_connected():
            cursor = connection.cursor()

            # Query untuk menghapus data peminjaman berdasarkan ID Buku
            sql_delete_query = """ 
                DELETE FROM peminjaman
                WHERE uid_buku = %s 
            """
            cursor.execute(sql_delete_query, (uid,))
            connection.commit()

            if cursor.rowcount > 0:
                print(f"Data dengan ID Buku {uid} berhasil dihapus dari database.")
            else:
                print(f"Tidak ada data dengan ID Buku {uid} yang ditemukan.")

    except Error as e:
        print(f"Error saat menghubungkan ke MySQL: {e}")

    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()

# Loop untuk membaca data dari Arduino
while True:
    if ser.in_waiting > 0:
        # Baca data dari serial (UID dari NFC)
        uid_raw = ser.readline().decode('utf-8').strip()
        if uid_raw:
            print(f"UID terbaca: {uid_raw}")

            # Ekstrak UID yang relevan dari string
            if "UID Kartu:" in uid_raw:
                uid = uid_raw.split("UID Kartu:")[1].strip()  # Mengambil bagian setelah 'UID Kartu:'
            else:
                uid = uid_raw  # Jika tidak ada format yang diharapkan

            # Hapus data dari database berdasarkan ID Buku
            delete_from_database(uid)
