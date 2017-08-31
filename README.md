# TYPO3 Extension realurlconflicts

Show realurl conflicts in a backend module. 
There are some possibilities to delete caching entries from realurl, but the main goal of this extension is, to just
show where the conflicts are. Solving conflicts must be done without just deleting caches. 
To resolve a conflict, an editor should rename or move a page in backend. 
Features in realurl like `Speaking URL path segment`, `Exclude from speaking URL` or `Override the whole page path`
make this task even harder for editors.

## Installation

* Install this extension like `composer require in2code/realurlconflicts`
* Just use the backend module

## Screenshots

<img src="https://s.nimbus.everhelper.me/attachment/1092708/eqyez9ps0lkavbmwgfhq/262407-H63IiNYwUikHpBbU/screen.png" />

## Requirements

| Software    | Versions   |
| ----------- | ---------- |
| TYPO3       | 8.7        |
| PHP         | 7.0 - 7.x  |

## Changelog

| Version    | Date       | State      | Description                                                                  |
| ---------- | ---------- | ---------- | ---------------------------------------------------------------------------- |
| 0.1.0      | 2017-08-31 | Initial    | Initial release                                                              |

## What's next

- Testing
- Also look at tx_realurl_urldata and not only tx_realurl_pathdata
