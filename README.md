# EXT:showpageeditors
[![TYPO3 badge](https://shields.io/endpoint?url=https://typo3-badges.dev/badge/showpageeditors/extension/shields)](https://extensions.typo3.org/extension/showpageeditors)
[![Latest Stable Version](http://poser.pugx.org/jpmschuler/showpageeditors/v)](https://packagist.org/packages/jpmschuler/showpageeditors)
[![Total Downloads](http://poser.pugx.org/jpmschuler/showpageeditors/downloads)](https://packagist.org/packages/jpmschuler/showpageeditors)
[![License](http://poser.pugx.org/jpmschuler/showpageeditors/license)](https://packagist.org/packages/jpmschuler/showpageeditors)
[![Build Status](https://github.com/jpmschuler/typo3-showpageeditors/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/jpmschuler/typo3-showpageeditors/actions/workflows/ci.yml)

[![PHP Version Require](http://poser.pugx.org/jpmschuler/showpageeditors/require/php)](https://packagist.org/packages/jpmschuler/showpageeditors)
[![TYPO3 V9](https://img.shields.io/badge/TYPO3-9-orange.svg)](https://get.typo3.org/version/9)
[![TYPO3 V10](https://img.shields.io/badge/TYPO3-10-orange.svg)](https://get.typo3.org/version/10)
[![TYPO3 V11](https://img.shields.io/badge/TYPO3-11-orange.svg)](https://get.typo3.org/version/11)
[![TYPO3 V12](https://img.shields.io/badge/TYPO3-12dev-orange.svg)]([https://get.typo3.org/version/12](https://packagist.org/packages/typo3/cms-core#dev-main))


This TYPO3 extension provides a cli command to retrieve every editor which has a
page in the backend tree by retrieving users, groups and subgroups with access.

```sh
# just run the command "page:showVisibilityFor <pid>", e.g.
/vendor/bin/typo3cms page:showVisibilityFor 4

Showing all pages up to TYPO3 root and all groups and users
which have access on each level (and thus below).
This does not consider page access, but only db mounts.

Page visibility for pid 4:
PID_0       TYPO3 root
 GID_0      Admin-flagged users
 \UID_1     admin
 \UID_2     _cli_
PID_1       1
 GID_3      editors of 1
 \UID_3     editor1
PID_4       1.2
 GID_4      editors of 1.2
 \UID_5     editor12
```

|                  | URL                                                     |
|------------------|---------------------------------------------------------|
| **Repository:**  | https://github.com/jpmschuler/typo3-showpageeditors     |
| **TER:**         | https://extensions.typo3.org/extension/showpageeditors  |
