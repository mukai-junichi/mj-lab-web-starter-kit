# Origin Theme

Vite + SCSS + ES Modules でビルドする WordPress ベーステーマです。

## 開発

```bash
npm install
npm run build    # 本番用ビルド
npm run watch    # 変更を監視してビルド
npm run dev      # Vite 開発サーバー（localhost:3000。.vite-dev ファイルがあるとテーマが Vite を参照）
```

## 標準装備

- **パフォーマンス**: スクリプト defer、HTTPS アセット強制
- **セキュリティ**: XML-RPC 無効、バージョン非表示、セルフピンバック停止
- **SEO**: 構造化データ（JSON-LD）、SEO SIMPLE PACK 用フォールバック

## 構成

- `src/scss/` → `assets/css/main.css`
- `src/js/main.js` → `assets/js/main.js`
- `inc/structured-data.php` … Organization / WebSite の JSON-LD
- `inc/seo-fallback.php` … メタタイトル・説明の補完（プラグイン連携）
