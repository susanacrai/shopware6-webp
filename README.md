# Webp for Shopware 6, based in Yireo Webp for Shopware 6
https://github.com/yireo-shopware6/shopware6-webp
This extension adds a Twig filter `webp` for usage in your Twig templates.

## Installation
```bash
composer require yby/shopware6-webp
bin/console plugin:refresh
bin/console plugin:install --activate YbyWebp
```

## Usage
File `src/Resources/views/storefront/layout/header/logo.html.twig`
```twig
{% sw_extends '@Storefront/storefront/layout/header/logo.html.twig' %}

{% block layout_header_logo_image_default %}
    {% if shopware.theme['sw-logo-desktop'] %}
        <source srcset="{{ shopware.theme['sw-logo-desktop'] | webp | sw_encode_url }}"
            alt="{{ "header.logoLink"|trans|striptags }}"
            class="img-fluid header-logo-main-img"
            type="image/webp"
        />
    {% endif %}
    {{ parent() }}
{% endblock %}
```