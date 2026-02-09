# MJ-Lab Web Starter Kit

WordPress 開発をすぐに始められる Docker ベースのスターターキットです。
**Windows / Mac** のどちらでも同じ手順で動かせます。

---

## 必要なもの

- **Docker Desktop** がインストールされ、起動していること
  - [Docker のダウンロード](https://www.docker.com/products/docker-desktop/)
- **（任意）Node.js 18 以上** … テーマの CSS/JS（SCSS や JavaScript）を編集する場合に必要です。
  **テーマの見た目や動きを変えない**なら、Node.js は不要で「手順 2」までで完了です。

### Node.js を用意する場合（テーマを編集するとき）

1. **インストール**
   - [Node.js の公式サイト](https://nodejs.org/) から **LTS 版** をダウンロードしてインストールする。
   - または [nvm](https://github.com/nvm-sh/nvm) を使っている場合は `nvm install 20` などで 18 以上を入れる。
2. **バージョン確認**
   ターミナルで次を実行し、`v18.0.0` のように 18 以上と表示されればOKです。
   ```bash
   node -v
   ```
3. このあと **手順 3** に進み、テーマフォルダで `npm install` → `npm run build` を実行します。

---

## 手順 1: キットを用意する

1. このリポジトリを **ZIP でダウンロード** するか、`git clone` する。
2. 解凍したフォルダを開く。フォルダ名は **`mj-lab-web-starter-kit`** になっています。
3. **（任意）フォルダ名を変えたい場合**
   - **Mac**: Finder でフォルダを 1 回クリックして選択し、もう 1 回ゆっくりクリックするか、右クリック → **名前を変更** で好きな名前にする（例: `my-site`）。
   - **Windows**: フォルダを右クリック → **名前の変更** で好きな名前にする（例: `my-site`）。
   - 名前を変えた場合は、**手順 2 以降で `cd` するときは、変更後のフォルダ名（パス）を使ってください。**
4. 必要に応じて `.env.example` をコピーして `.env` を作成し、値を変更できます（そのままでOKです）。

---

## 手順 2: Docker で WordPress を起動する

1. **ターミナルを開く**
   - **Mac**: Spotlight（`Command + スペース`）で「ターミナル」と入力して **ターミナル** を起動。または **Finder → アプリケーション → ユーティリティ → ターミナル**。
   - **Windows**: **Windows キー** を押して「cmd」または「PowerShell」と入力し、**コマンドプロンプト** または **Windows PowerShell** を起動。または **スタートメニュー → Windows システムツール → コマンドプロンプト**。
2. キットのフォルダに移動する：
   ```bash
   cd 解凍した場所のパス/キットのフォルダ名
   ```
   - フォルダ名を変えていない場合: `mj-lab-web-starter-kit`
   - 例（Mac）: `cd /Users/あなたの名前/projects/mj-lab-web-starter-kit` または `cd /Users/あなたの名前/projects/my-site`（名前を変えた場合）
   - 例（Windows）: `cd C:\Users\あなたの名前\projects\mj-lab-web-starter-kit` または `cd C:\Users\あなたの名前\projects\my-site`（名前を変えた場合）
3. 次のコマンドを実行する：
   ```bash
   docker-compose up -d
   ```
4. 初回はイメージのダウンロードに数分かかることがあります。
5. ブラウザで **http://localhost:8080** を開く。
6. 画面の指示に従って WordPress をインストール（言語・サイト名・ユーザー名・パスワードを設定）。
7. インストールが終わったら、**外観 → テーマ** から **「Origin」** を有効化する。

これで開発環境の立ち上げは完了です。

---

## 手順 3: テーマの CSS/JS を編集する場合（任意）

デザインや動きを変えたいときは、テーマ内の **Vite** でビルドします。
**この手順を行うには、上記「Node.js を用意する場合」で Node.js 18 以上をインストールしておいてください。**

1. ターミナルで、**キットのルート**（`mj-lab-web-starter-kit` がある場所）にいる状態で、**テーマのフォルダ** に移動する：
   ```bash
   cd wp-content/themes/origin
   ```
2. 依存関係をインストールする（初回のみ）：
   ```bash
   npm install
   ```
3. 本番用に CSS/JS を一度ビルドする：
   ```bash
   npm run build
   ```
   - これで `assets/css/main.css` と `assets/js/main.js` が生成されます。
4. 開発中に変更を反映し続けたいときは、別ターミナルで：
   ```bash
   npm run watch
   ```
   - ファイルを保存するたびに自動で再ビルドされます。
   - **Docker 内で watch する場合**（WSL2 やリモートでファイルを共有している場合）は、
     `VITE_USE_POLLING=1 npm run watch` を試してください。

---

## よく使うコマンド

| やりたいこと           | コマンド |
|------------------------|----------|
| WordPress を止める     | `docker-compose down` |
| もう一度起動する       | `docker-compose up -d` |
| ログを確認する         | `docker-compose logs -f wordpress` |
| テーマをビルドする     | `cd wp-content/themes/origin && npm run build` |

---

## デプロイ（本番サーバーへアップロード）

**エックスサーバー** などに本番反映する手順は **[DEPLOY.md](./DEPLOY.md)** にまとめています。

- **rsync（Sync）**: SSH が使える場合はこちらがおすすめ。コマンド一発でテーマのみ同期できます。
- **SFTP**: SSH がなくても、FileZilla 等でアップロードできます。

デプロイ前に、テーマフォルダで `npm run build` を実行してください。

---

## フォルダ構成（抜粋）

※ 一番上のフォルダ名（初期は `mj-lab-web-starter-kit`）は、手順 1 で好きな名前に変更できます。

```
mj-lab-web-starter-kit/
├── docker-compose.yml      … WordPress と MySQL の定義
├── .env.example            … 環境変数の例（コピーして .env に）
├── php/
│   └── php.ini             … PHP 設定（アップロードサイズ等）
├── wp-content/
│   └── themes/
│       └── origin/         … ベーステーマ（Vite + SCSS + 最適化済み）
│           ├── src/        … ソース（scss, js）
│           ├── assets/     … ビルド出力（npm run build で生成）
│           ├── inc/        … SEO・構造化データ
│           └── style.css, functions.php, 各種テンプレート
├── README.md               … このファイル
└── DEPLOY.md               … 本番デプロイ手順（エックスサーバー想定）
```

---

## トラブルシュート

- **ポート 8080 が使えない**
  `docker-compose.yml` の `ports: "8080:80"` を `"8888:80"` など別ポートに変更してください。
- **テーマが一覧に出てこない**
  `wp-content/themes/origin` に `style.css` と `functions.php` があるか確認してください。
- **CSS が反映されない**
  テーマフォルダで `npm run build` を実行し、`assets/css/main.css` ができているか確認してください。

---

## ライセンス

GPL v2 or later（WordPress テーマとして）
