BOXAR'S EDGE — LINKED GAME BUILD

Canonical navigation:
  Main menu: Boxars_edge_UI.html
  Gameplay:  boxar-game-level.html

Compatibility entry points retained:
  Boxars_edge_level.html
  boxars-edge-level-tomiwa.html
  boxars-edge-ui-preview-controller.html

Both gameplay compatibility files route to boxar-game-level.html.
The old UI controller routes to Boxars_edge_UI.html.

REGULAR PLAY
Main Menu -> Character Select -> boxar-game-level.html -> Match Result
-> Character Select / Main Menu / Rematch.

ARCADE PLAY
Main Menu -> Arcade Setup -> Character Select -> Arcade Ladder
-> boxar-game-level.html -> Match Result -> Arcade Ladder.
The player is not forced into the next fight.

Arcade progression retained in localStorage:
  - 3 retries
  - ladder stage / wins
  - 3,000 points per win
  - five upgradeable attributes
  - 2,000 points per attribute level
  - five levels maximum
  - 10% boost per level
  - progress is separate from regular play
  - save/load Arcade progress as JSON from the Arcade Ladder
  - Amara remains registered as Amara.glb

Attribute effects in Arcade only:
  Punch Power, Health, Stamina Regeneration, Boxer's Edge Damage,
  Stun Resistance.

The regular game path does not consume Arcade upgrades.
