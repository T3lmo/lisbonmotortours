# Lisbon MotorTours: pré-visualização no portfolio

Este guia põe o site Lisbon MotorTours como **pré-visualização de cliente** no teu portfolio WordPress Multisite (ver o README do portfolio, secções 4 e 5).

## Ficheiros (pasta `Apps\lisbonmotortours`)

| Ficheiro | Para quê |
|---|---|
| `dist/lisbon-motortours.zip` | O tema (aspeto, tours e campos dos tours). Já não precisa de ACF nem de plugins. |
| `lisbon-motortours-conteudo.xml` | Páginas (Home, About, Contact, Terms), o tour de exemplo e as imagens |
| `media/` | Imagens e PDFs que o XML importa. **Têm de estar no GitHub antes de importar** (ver passo 3). |

## 1. Criar a pré-visualização

No portfolio: **Portfolio → Client previews**.

1. Cliente `Lisbon MotorTours`, endereço `lisbon-motortours`, palavra-passe gerada, tema inicial qualquer.
2. Deixa "Also add a draft project" marcado.
3. **Create preview** e abre o **Dashboard** do novo site.

## 2. Instalar o tema

1. **Network Admin → Themes → Add New → Upload** `dist/lisbon-motortours.zip`. Não ativar para toda a rede.
2. **Network Admin → Sites → (a pré-visualização) → Themes**: ativar aqui o tema.
3. Na pré-visualização: **Appearance → Themes → Activate**.

## 3. Importar o conteúdo

1. Faz push da pasta `media/` para `github.com/T3lmo/lisbonmotortours` (ramo `main`). O XML vai buscar as imagens a `raw.githubusercontent.com/T3lmo/lisbonmotortours/main/media/`. O repositório tem de ser público, ou muda o endereço no topo de `tools/build-content.js` e corre o script outra vez.
2. **Tools → Import → WordPress → Install now → Run Importer**, escolhe `lisbon-motortours-conteudo.xml`, marca **Download and import file attachments** e importa.

## 4. Definições do site

- **Settings → General**: Site Title `Lisbon MotorTours`, Tagline `Motorcycle adventures across the World`.
- **Settings → Reading**: "A static page" e escolhe **Home** como Homepage.
- **Settings → Permalinks**: "Post name" e guardar.
- **Appearance → Menus** (opcional): o tema mostra Home, Tours, About, Contact e Terms sozinho. Só é preciso criar um menu para mudar isso, e atribuí-lo a "Main Menu".

## 5. Rever o conteúdo

- **Tours → Tour aos Cárpatos!**: caixa **Tour details** com datas, lugares, preço, os três PDFs e, opcionalmente, o shortcode do formulário (por exemplo `[fluentform id="5"]`). Sem shortcode aparece um botão de email para o email de administração do site.
- As datas do tour de exemplo foram passadas para **12 a 20 de junho de 2027**, porque as de 2026 já tinham passado e a página Tours só mostra tours futuros. Muda-as para as datas reais.
- Campo **Programme PDF** vazio: o programa que existia na base de dados era um PDF de teste.
- Página **Contact**: o formulário foi retirado (era Fluent Forms). Se o instalares na pré-visualização, cola o shortcode no bloco de texto da coluna da direita.

## 6. Adicionar ao portfolio

Em **Portfolio → Add project** (ou edita o rascunho criado no passo 1):

- Tipo `wordpress`, estado `Awaiting approval`, cliente `Lisbon MotorTours`, ano `2026`.
- **Preview URL**: o endereço da pré-visualização.
- Stack: `WordPress, tema à medida, custom post type`.
- Imagem de destaque: uma captura de ecrã da página inicial, em 16:10.

## Antes de ir para o cliente

- **Vídeo da página inicial**: `assets/video/hero.mp4` foi comprimido de 36 MB para 5 MB (1280×720, sem som). O original está em `Local Sites\lisbon-motortours`.
- **Rodapé**: o RNAAT está como `000/2026` (provisório) em `footer.php`.
- **Email do contacto**: no site antigo o texto dizia `info@lisbonmotortours.com` mas o link era `.pt`. Fica `.pt`; confirma qual é o correto.
- Para mover para o alojamento do cliente, segue a secção 5 do README do portfolio (Prime Mover).
