# Schránka Důvěry (Trust Box)

Bezpečná anonymní zpráva systém pro sdílení myšlenek, obav nebo zpovědi.

## Funkce

- 🔒 Anonymní odesílání zpráv
- 📧 Email notifikace pro administrátora
- 🎨 Moderní a responzivní design
- 🛡️ Ochrana soukromí
- ✨ Animace a interaktivní prvky

## Konfigurace

Před nasazením upravte následující proměnné v `index.php`:

```php
$admin_email = "vase@email.cz"; // Váš email pro příjem zpráv
$site_name = "Schránka Důvěry"; // Název webu
```

## Nasazení na Coolify

1. Vytvořte nový projekt v Coolify
2. Připojte tento Git repozitář
3. Coolify automaticky detekuje PHP a použije Nixpacks
4. Nastavte environment proměnné (pokud potřebujete)
5. Nasaďte aplikaci

## Požadavky

- PHP 8.2+
- Funkční mail() funkce na serveru
- Nginx + PHP-FPM (automaticky nakonfigurováno Nixpacks)

## Struktura

- `index.php` - Hlavní aplikace
- `nixpacks.toml` - Konfigurace pro Nixpacks deployment
