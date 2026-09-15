# Boxar's Edge

PC motion-based boxing game, built with Three.js + MediaPipe. Distributed as self-contained HTML apps (single-file build per mode).

Open an HTML entry point through a local web server or the deployment environment (not directly from the filesystem) so browser security rules do not block model and audio loading.

## Play

- **Website / store page:** `index.html`
- **Main game menu:** `game/menu.html`
- **Arcade ring:** `game/modes/arcade/ring.html`
- **Career mode:** `game/modes/career/fight.html`
- **Protagonist mode:** `game/modes/protagonist/fight.html`
- **Heavy bag trainer:** `game/modes/training/heavybag.html`
- **Read me first (controls):** `game/pages/how-to-fight.html`

## Project layout

- `game/` — the playable apps.
  - `menu.html` — main menu / UI shell (also `menu-preview.html`).
  - `pages/` — static support pages (how-to-fight, fighters roster).
  - `modes/arcade/` — ring, ring-hdri, lagos builds.
  - `modes/career/` — fight, bridge, character-select.
  - `modes/protagonist/` — fight, sparring, act1-final-boss, bridge.
  - `modes/training/` — heavy bag.
- `web/` — web-specific pages (e.g. `purchase-success.html`).
- `assets/` — runtime media, grouped by type: models, images, audio, video, HDRI, UI effects.
- `server/` — purchase/download PHP endpoints.
- `data/` — save files and progress templates.
- `source/` — editable design and 3D source files (FBX, Illustrator, After Effects).
- `docs/` — build notes, original readmes, and `asset-move-manifest.json`.
- `protected/` — protected digital-delivery content used by the store server.
- `archive/2026-09-14/` — dated snapshot of repeated/redundant copies (old builds, backup pages, duplicate media, legacy tools). Recoverable, not part of the live game.

## Git / hosting

- The repository includes the full runtime `assets/` tree (~3 GB) so any clone is self-hosting.
- Everything under `archive/` is excluded from Git; it exists only in local copies.
- Once push is initially large, later pushes are incremental (compress as much as GitHub allows; `.git` also holds media blobs).