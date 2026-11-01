const BS = String.fromCharCode(92);
function parseRow(l) {
  const i = l.indexOf('VALUES (');
  let p = i + 8;
  const v = [];
  while (true) {
    let c = l[p];
    if (c === "'") {
      let s = '';
      p++;
      while (true) {
        c = l[p];
        if (c === BS) {
          const n = l[p + 1];
          s += n === 'n' ? '\n' : n === 'r' ? '\r' : n === '0' ? '\0' : n;
          p += 2;
        } else if (c === "'" && l[p + 1] === "'") { s += "'"; p += 2; }
        else if (c === "'") { p++; break; }
        else { s += c; p++; }
      }
      v.push(s);
    } else {
      let e = p;
      while (l[e] !== ',' && l[e] !== ')') e++;
      const t = l.slice(p, e);
      v.push(t === 'NULL' ? null : t);
      p = e;
    }
    if (l[p] === ',') { p++; continue; }
    break;
  }
  return v;
}
exports.table = (sql, t) =>
  sql.split('\n')
    .filter(l => l.startsWith('INSERT INTO `SERVMASK_PREFIX_' + t + '` VALUES'))
    .map(parseRow);
