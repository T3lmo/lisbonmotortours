# Lisbon MotorTours

WordPress theme and demo content for the Lisbon MotorTours website (motorcycle tours in Romania), made to run as a client preview on the portfolio Multisite.

```
wp-content/themes/lisbon-motortours/   the theme (tours post type with its own fields, no plugins needed)
media/                                 images and PDFs imported by the content file
lisbon-motortours-conteudo.xml         pages + example tour (WordPress importer)
tools/                                 scripts that rebuild the content file from the old site backup
dev/                                   local test setup (WordPress Playground)
dist/lisbon-motortours.zip             the theme, ready to upload
GUIA-INSTALACAO.md                     step by step install (Portuguese)
```

Local test (Node.js, PowerShell): `npx -y @wp-playground/cli@3.1.61 server --port=9403 --blueprint=./dev/blueprint.json --mount=./wp-content/themes/lisbon-motortours:/wordpress/wp-content/themes/lisbon-motortours --mount=./dev:/wordpress/dev --mount=./media:/wordpress/media`
