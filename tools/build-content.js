// Builds lisbon-motortours-conteudo.xml (WordPress import file) from the old site's database.
//
//   node tools/wpress.js <backup.wpress> <folder> database.sql
//   node tools/build-content.js <folder>/database.sql
//
// Media files live in media/ and are imported from MEDIA_BASE, so push them to GitHub first.
const fs = require('fs');
const path = require('path');
const { table } = require('./sqlp.js');

const MEDIA_BASE = process.env.MEDIA_BASE || 'https://raw.githubusercontent.com/T3lmo/lisbonmotortours/main/media/';
const SITE = 'https://lisbonmotortours.invalid';
const OLD_UPLOADS = 'http://lisbon-motortours.local/wp-content/uploads/2025/12/';

const sql = fs.readFileSync(process.argv[2], 'utf8');
const posts = Object.fromEntries(table(sql, 'posts').map((p) => [p[0], p]));

const esc = (s) => String(s).replace(/]]>/g, ']]]]><![CDATA[>');
const cdata = (s) => `<![CDATA[${esc(s)}]]>`;

let nextId = 1;
const items = [];

function item({ title, name, type, content = '', excerpt = '', parent = 0, order = 0, date, status = 'publish', meta = {}, attachmentUrl, menu }) {
  const id = nextId++;
  items.push(`
  <item>
    <title>${cdata(title)}</title>
    <link>${SITE}/${name}/</link>
    <guid isPermaLink="false">${SITE}/?p=${id}</guid>
    <description></description>
    <content:encoded>${cdata(content)}</content:encoded>
    <excerpt:encoded>${cdata(excerpt)}</excerpt:encoded>
    <wp:post_id>${id}</wp:post_id>
    <wp:post_date>${cdata(date)}</wp:post_date>
    <wp:post_date_gmt>${cdata(date)}</wp:post_date_gmt>
    <wp:post_modified>${cdata(date)}</wp:post_modified>
    <wp:post_modified_gmt>${cdata(date)}</wp:post_modified_gmt>
    <wp:comment_status>${cdata('closed')}</wp:comment_status>
    <wp:ping_status>${cdata('closed')}</wp:ping_status>
    <wp:post_name>${cdata(name)}</wp:post_name>
    <wp:status>${cdata(type === 'attachment' ? 'inherit' : status)}</wp:status>
    <wp:post_parent>${parent}</wp:post_parent>
    <wp:menu_order>${order}</wp:menu_order>
    <wp:post_type>${cdata(type)}</wp:post_type>
    <wp:post_password>${cdata('')}</wp:post_password>
    <wp:is_sticky>0</wp:is_sticky>${menu ? `
    <category domain="nav_menu" nicename="${menu}">${cdata('Main Menu')}</category>` : ''}${attachmentUrl ? `
    <wp:attachment_url>${cdata(attachmentUrl)}</wp:attachment_url>` : ''}${Object.entries(meta).map(([k, v]) => `
    <wp:postmeta>
      <wp:meta_key>${cdata(k)}</wp:meta_key>
      <wp:meta_value>${cdata(v)}</wp:meta_value>
    </wp:postmeta>`).join('')}
  </item>`);
  return id;
}

const DATE = '2025-12-26 20:00:00';
const attach = (file, title, parent = 0) =>
  item({ title, name: path.parse(file).name.toLowerCase(), type: 'attachment', parent, date: DATE, attachmentUrl: MEDIA_BASE + file });

// ---- media
const logo = attach('image.png', 'Lisbon MotorTours');
const thumb = attach('GM251010A-1920w.webp', 'Cárpatos');

// ---- pages
const home = item({ title: posts[5][5], name: 'home', type: 'page', date: DATE, content: posts[5][4].replace(/<!-- wp:spacer[\s\S]*?<!-- \/wp:spacer -->\s*/g, '').trim() });

const contact = item({
  title: 'Contact', name: 'contact', type: 'page', date: DATE, order: 2,
  content: `<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"className":"section-title"} -->
<h2 class="wp-block-heading section-title">Lisbon MotorTours</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>R. Mártires de Timor 24, 2685-331 Prior Velho</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Email: <a href="mailto:info@lisbonmotortours.pt">info@lisbonmotortours.pt</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Phone: +351 965 44 55</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d10434.134833601109!2d-9.131869136514336!3d38.78763491068905!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd19326e05db3ce7%3A0xeb71da59251984d2!2sLisbon%20Motorcycles!5e1!3m2!1spt-PT!2spt!4v1766542224401!5m2!1spt-PT!2spt" width="600" height="450" style="border:0;max-width:100%" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Mapa"></iframe>
<!-- /wp:html --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"className":"section-title"} -->
<h2 class="wp-block-heading section-title">What can we do for you?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Escreva-nos para <a href="mailto:info@lisbonmotortours.pt">info@lisbonmotortours.pt</a>. (Formulário: adicionar aqui o shortcode do Fluent Forms ou do Contact Form 7.)</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->`,
});

const termsContent = posts[28][4].split(OLD_UPLOADS + 'image-1024x329.png').join(MEDIA_BASE + 'image.png');
const terms = item({ title: 'Terms', name: 'terms', type: 'page', date: DATE, order: 3, content: termsContent });

// ---- menu (same order as on the Local site)
const menuItem = (order, fields) => item({ title: '', name: String(order), type: 'nav_menu_item', date: DATE, order, menu: 'main-menu', meta: { _menu_item_menu_item_parent: '0', _menu_item_target: '', _menu_item_classes: 'a:1:{i:0;s:0:"";}', _menu_item_xfn: '', _menu_item_url: '', ...fields } });
menuItem(1, { _menu_item_type: 'post_type', _menu_item_object: 'page', _menu_item_object_id: String(home) });
// The importer drops post-type-archive menu items, so Tours is a custom link; the theme points it at this site (see lmt_menu_site_url).
menuItem(2, { _menu_item_type: 'custom', _menu_item_object: 'custom', _menu_item_object_id: '0', _menu_item_url: SITE + '/tours/' });
items[items.length - 1] = items[items.length - 1].replace('<title><![CDATA[]]></title>', '<title><![CDATA[Tours]]></title>');
menuItem(3, { _menu_item_type: 'post_type', _menu_item_object: 'page', _menu_item_object_id: String(contact) });
menuItem(5, { _menu_item_type: 'post_type', _menu_item_object: 'page', _menu_item_object_id: String(terms) });

// ---- tour
// Same data as the Local site. Note: the tours page only lists tours that have not started yet.
const t = posts[102];
const tm = Object.fromEntries(table(sql, 'postmeta').filter((m) => m[1] === '102').map((m) => [m[2], m[3]]));
const tour = item({
  title: t[5], name: t[11], type: 'tour', date: DATE, content: t[4], excerpt: t[6],
  meta: {
    _thumbnail_id: String(thumb),
    tour_date: tm.tour_date,
    tour_end_date: tm.tour_end_date,
    tour_price: tm.tour_price,
    tour_max_riders: tm.tour_max_riders,
    tour_programa: MEDIA_BASE + 'Seleccoes-do-Readers-Digest.pdf',
    tour_termos: MEDIA_BASE + 'temrs.pdf',
    tour_brochura: MEDIA_BASE + 'brochura_carpatos_harley.pdf',
    tour_form: '',
  },
});
// slider images: attached to the tour
['pexels-fernanda-w-corso-2149339004-32031408-1.jpg', 'Carpatos-1.jpg', 'Carpatos-montanha-trekking-01_ctRovC6-1.jpg']
  .forEach((f) => attach(f, f.replace(/\.\w+$/, ''), tour));

const xml = `<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0"
  xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
  xmlns:content="http://purl.org/rss/1.0/modules/content/"
  xmlns:wfw="http://wellformedweb.org/CommentAPI/"
  xmlns:dc="http://purl.org/dc/elements/1.1/"
  xmlns:wp="http://wordpress.org/export/1.2/">
<channel>
  <title>Lisbon MotorTours</title>
  <link>${SITE}</link>
  <description>Motorcycle adventure across Romania</description>
  <language>pt-PT</language>
  <wp:wxr_version>1.2</wp:wxr_version>
  <wp:base_site_url>${SITE}</wp:base_site_url>
  <wp:base_blog_url>${SITE}</wp:base_blog_url>
  <wp:term><wp:term_id>1</wp:term_id><wp:term_taxonomy>nav_menu</wp:term_taxonomy><wp:term_slug>main-menu</wp:term_slug><wp:term_name>${cdata('Main Menu')}</wp:term_name></wp:term>
${items.join('\n')}
</channel>
</rss>
`;

const out = process.env.OUT || path.join(__dirname, '..', 'lisbon-motortours-conteudo.xml');
fs.writeFileSync(out, xml);
console.log('wrote', out, items.length, 'items');
