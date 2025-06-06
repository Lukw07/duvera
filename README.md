# Schránka Důvěry - Anonymous Messaging System

Bezpečná aplikace pro anonymní zasílání zpráv s podporou SMTP emailů přes Forpsi.

## Funkce

- 📝 Anonymní odesílání zpráv
- 📧 SMTP email notifikace
- 🔒 Bezpečné šifrované spojení
- 🎨 Moderní responzivní design
- 🇨🇿 Česká lokalizace
### 2. Konfigurace aplikace

Upravte soubor `config.php`:

```php
'smtp' => [
    'host' => 'smtp.forpsi.com',
    'port' => 587, // TLS port (doporučeno)
    'encryption' => 'tls',
    'username' => 'noreply@zskamenicka.cz',
    'password' => 'VAŠE_HESLO_ZDE', // ⚠️ Nastavte skutečné heslo
    'from_email' => 'noreply@zskamenicka.cz',
    'from_name' => 'Schránka Důvěry - ZŠ Kamenická',
],
'admin' => [
    'email' => 'admin@zskamenicka.cz', // Email pro příjem zpráv
    'name' => 'Administrátor'
]
```

### 3. SMTP nastavení

- **SMTP server:** `smtp.?.com`
- **Port:** `587` (TLS) nebo `465` (SSL)
- **Šifrování:** TLS nebo SSL (povinné)
- **Autentifikace:** Povinná (LOGIN/PLAIN)
- **Uživatelské jméno:** Celá emailová adresa
- **Heslo:** Heslo k emailovému účtu

### 4. Testování SMTP

### Požadavky

- PHP 8.2+
- Composer
- PHPMailer
- Funkční emailový účet

### Kroky nasazení

1. **Klonování repozitáře**

   ```bash
   git clone <repository-url>
   cd duvera
   ```

2. **Konfigurace SMTP**

   - Upravte `config.php` s vašimi SMTP údaji
   - Nastavte správné heslo pro `noreply@zskamenicka.cz`

3. **Nasazení v Coolify**

   - Vytvořte nový projekt
   - Vyberte "Git Repository"
   - Nastavte build pack na "Nixpacks"
   - Aplikace se automaticky sestaví s `composer install`

4. **Testování**
   - Navštivte `/smtp-test.php` pro test SMTP
   - Otestujte hlavní formulář
   - Smažte testovací soubory

## Struktura projektu

```
duvera/
├── index.php              # Hlavní aplikace
├── config.php             # SMTP a aplikační konfigurace
├── composer.json          # PHP závislosti
├── src/
│   └── EmailService.php   # SMTP email služba
├── nixpacks.toml          # Coolify build konfigurace
├── smtp-test.php          # Test SMTP (smazat po testování)
└── README.md
```

## Bezpečnostní poznámky

- ✅ Všechny vstupy jsou sanitizovány
- ✅ SMTP komunikace je šifrovaná (TLS/SSL)
- ✅ Hesla nejsou logována
- ✅ IP adresy jsou zaznamenávány pro bezpečnost
- ⚠️ Smažte testovací soubory po nasazení
- ⚠️ Používejte silná hesla pro emailové účty

## Řešení problémů

### SMTP chyby

1. **Authentication failed**

   - Zkontrolujte uživatelské jméno a heslo
   - Ověřte, že účet `noreply@zskamenicka.cz` existuje

2. **Connection refused**

   - Zkontrolujte port (587 pro TLS, 465 pro SSL)
   - Ověřte, že stmp neblokuje připojení

3. **Sender rejected**
   - Odesílatel musí odpovídat autentifikačnímu účtu

## Licence

MIT License
