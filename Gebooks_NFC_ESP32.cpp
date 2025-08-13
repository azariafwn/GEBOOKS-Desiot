#include <SPI.h>
#include <Adafruit_PN532.h>

// Definisikan pin untuk SPI
#define PN532_SCK  (13)  // Clock
#define PN532_MISO (12)  // Master In Slave Out
#define PN532_MOSI (11)  // Master Out Slave In
#define PN532_SS   (10)  // Slave Select (Chip Select)

// Inisialisasi objek PN532
Adafruit_PN532 nfc(PN532_SS);

void setup(void) {
  Serial.begin(9600); // Komunikasi serial dengan ESP8266
  Serial.println("Inisialisasi Pembaca NFC PN532...");

  // Inisialisasi PN532 menggunakan SPI
  nfc.begin();

  // Cek apakah modul PN532 terdeteksi
  uint32_t versiondata = nfc.getFirmwareVersion();
  if (!versiondata) {
    Serial.println("Modul PN532 tidak ditemukan! Cek koneksi.");
    while (1); // Berhenti jika modul tidak terdeteksi
  }

  // Jika modul PN532 ditemukan, tampilkan versi firmware
  Serial.print("Ditemukan PN532 dengan firmware versi: ");
  Serial.println((versiondata >> 16) & 0xFF, HEX);

  // Konfigurasi PN532 untuk mode pembacaan pasif
  nfc.SAMConfig();
  Serial.println("Siap untuk membaca kartu NFC.");
}

void loop(void) {
  uint8_t success;
  uint8_t uid[] = { 0, 0, 0, 0, 0, 0, 0 }; // Buffer untuk menyimpan UID
  uint8_t uidLength;                       // Variabel untuk panjang UID

  // Mencoba membaca kartu NFC dalam mode pasif (ISO14443A)
  success = nfc.readPassiveTargetID(PN532_MIFARE_ISO14443A, uid, &uidLength);

  if (success) {
    // Jika kartu NFC berhasil terbaca
    //Serial.println("Kartu NFC terdeteksi!");
    String uidData = "";
    for (uint8_t i = 0; i < uidLength; i++) {
      uidData += String(uid[i], HEX);
      if (i < uidLength - 1) uidData += ":"; // Formatkan UID dengan pemisah ":"
    }
  } 
}
