# デプロイ手順（エックスサーバー想定）

エックスサーバーに、スターターキットで作ったサイト（テーマ Origin）をアップロードする手順です。

---

## 前提

- エックスサーバーで **WordPress をインストール済み**（クイックインストールまたは手動インストール）。
- 本番の **wp-content/themes/** にテーマ「Origin」を配置する想定です。
- デプロイ前に、ローカルで **テーマのビルド** を実行してください（`wp-content/themes/origin` で `npm run build`）。

---

## どの方法が楽か

| 方法 | おすすめ度 | 条件 |
|------|-------------|------|
| **rsync（Sync）** | ◎ 一番楽 | エックスサーバーで **SSH を有効化** していること。変更分だけ送れるので 2 回目以降が速い。 |
| **SFTP** | ○ 手軽 | SSH がなくても可。FileZilla や VS Code の SFTP 拡張などでアップロード。 |

**SSH が使えるなら rsync がおすすめ**です。一度設定すればコマンド一発で同期できます。

---

## 方法 1: rsync でデプロイ（おすすめ）

### 1. エックスサーバーで SSH を有効にする

1. エックスサーバーの **サーバーパネル** にログインする。
2. **「SSH 設定」** を開き、**SSH を有効にする**。
3. 必要に応じて公開鍵を登録する（パスワード認証のままでも rsync は使えます）。

### 2. 接続情報を確認する

- **ホスト名**: `xxx.xserver.jp`（サーバーパネルの「サーバー情報」で確認）
- **ユーザー名**: サーバーパネルに表示されている **「SSH のユーザー名」**（FTP とは別のことが多いです）
- **リモートのパス**: `/home/サーバーID/ドメイン名/public_html/wp-content/themes/origin`
  - 例: サーバーID が `examplecom`、ドメインが `example.com` なら
    `/home/examplecom/example.com/public_html/wp-content/themes/origin`

### 3. ローカルでテーマをビルドする

```bash
cd wp-content/themes/origin
npm run build
cd ../../..
```

（キットのルートに戻る想定です。パスは環境に合わせて調整してください。）

### 4. rsync でテーマを送る

**Mac / Linux（ターミナル）** の例です。

```bash
# キットのルートで実行
rsync -avz --delete \
  --exclude 'node_modules' \
  --exclude '.vite-dev' \
  --exclude '.git' \
  --exclude '.env' \
  wp-content/themes/origin/ \
  ユーザー名@xxx.xserver.jp:/home/サーバーID/ドメイン名/public_html/wp-content/themes/origin/
```

- **`-avz`**: アーカイブ・表示・圧縮。
- **`--delete`**: ローカルで消したファイルはサーバー側も削除（不要なら外してよい）。
- **`--exclude`**: 送らないフォルダ・ファイル。
- 末尾の **`/`** を忘れないでください（`origin/` のなかみを同期します）。

**Windows** の場合は、WSL や Git Bash で同じコマンドが使えます。または [cwRsync](https://itefix.net/cwrsync) などの rsync クライアントを利用できます。

### 5. 本番でテーマを有効化

WordPress 管理画面 → **外観 → テーマ** から **「Origin」** を有効化してください（まだの場合）。

---

## 方法 2: SFTP でデプロイ

SSH を使わない場合や、GUI で送りたい場合は SFTP クライアントを使います。

### 1. 接続情報

- **ホスト**: `xxx.xserver.jp` または **FTP ホスト名**（サーバーパネルの「FTP 設定」で確認）
- **プロトコル**: **SFTP**（FTP より安全）
- **ユーザー名 / パスワード**: FTP アカウントのもの（サーバーパネルで確認）
- **リモートのパス**: `public_html/wp-content/themes/` の下に **origin** フォルダを作り、その中にアップロード

### 2. アップロードするもの

ローカルの **`wp-content/themes/origin`** の中身を、サーバーの **`wp-content/themes/origin`** にそのままアップロードします。

**送らなくてよいもの**（あれば除外してよい）:

- `node_modules/`
- `.vite-dev`
- `.git/`

**送る前に** ローカルで `npm run build` を実行し、**`assets/css/main.css`** と **`assets/js/main.js`** ができていることを確認してください。

### 3. おすすめの SFTP クライアント

- **FileZilla**（無料）: [https://filezilla-project.org/](https://filezilla-project.org/)
- **VS Code**: 拡張機能「SFTP」や「Remote - SSH」でアップロード可能。

---

## デプロイ前チェックリスト

- [ ] テーマフォルダで `npm run build` を実行した
- [ ] `assets/css/main.css` と `assets/js/main.js` が存在する
- [ ] `.env` やローカル用の設定ファイルをサーバーに送っていない（本番はサーバーの環境に合わせる）
- [ ] 本番の WordPress で「Origin」テーマを有効化した

---

## 注意事項（エックスサーバー）

- **PHP バージョン**: 管理画面やサーバーパネルで PHP 7.4 以上を推奨。必要に応じて切り替え。
- **パーミッション**: アップロード後、ファイルは 644、ディレクトリは 755 が一般的。不具合が出たらサーバーパネルのマニュアルを参照。
- **SSL**: 本番を HTTPS にする場合は、エックスサーバーで「SSL 設定」を有効にしてください。

---

## トラブルシュート

- **rsync で「Permission denied」**
  SSH のユーザー名・パスワード（または鍵）が正しいか、サーバー側パスが存在するか確認。
  先に SFTP で `wp-content/themes/origin` フォルダを手動作成してから rsync してもよい。

- **CSS が反映されない**
  本番の `wp-content/themes/origin/assets/css/main.css` が存在するか確認。
  なければローカルで `npm run build` を実行してから再アップロード。

- **テーマが一覧に出てこない**
  `style.css` と `functions.php` がサーバーの `origin` フォルダに含まれているか確認。
