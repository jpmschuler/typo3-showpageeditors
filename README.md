# EXT:showpageeditors
[![TYPO3 badge](https://shields.io/endpoint?url=https://typo3-badges.dev/badge/showpageeditors/extension/shields)](https://extensions.typo3.org/extension/showpageeditors)
[![Latest Stable Version](http://poser.pugx.org/jpmschuler/showpageeditors/v)](https://packagist.org/packages/jpmschuler/showpageeditors)
[![Total Downloads](http://poser.pugx.org/jpmschuler/showpageeditors/downloads)](https://packagist.org/packages/jpmschuler/showpageeditors)
[![Latest Unstable Version](http://poser.pugx.org/jpmschuler/showpageeditors/v/unstable)](https://packagist.org/packages/jpmschuler/showpageeditors)
[![License](http://poser.pugx.org/jpmschuler/showpageeditors/license)](https://packagist.org/packages/jpmschuler/showpageeditors)
[![PHP Version Require](http://poser.pugx.org/jpmschuler/showpageeditors/require/php)](https://packagist.org/packages/jpmschuler/showpageeditors)
[![TYPO3 V12](https://img.shields.io/badge/TYPO3-12-orange.svg)]([https://get.typo3.org/version/12](https://packagist.org/packages/typo3/cms-core#dev-main))


This TYPO3 extension provides
- a cli command to retrieve every editor who has a page in the backend tree by
retrieving users, groups and subgroups with access.
- a cli command to retrieve every editor who is part of a group

These come in handy for quick analyzing of group inheritance, or for a regular
check on important privileges.

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


```sh
# just run the command "begroup:showMembers <gid>", e.g.
/vendor/bin/typo3 begroup:showMembers 1                                                                        ✔  9948  10:33:55
Group members of group 1 (myGroup) and other groups inheriting from it:
 GID_1      myGroup
 \UID_3     editor3
 \UID_5     editor5
 GID_125    mySubGroup
 \UID_17    editor17
```

|                 | URL                                                     |
|-----------------|---------------------------------------------------------|
| **Repository:** | https://github.com/jpmschuler/typo3-showpageeditors     |
| **TER:**        | https://extensions.typo3.org/extension/showpageeditors  |
| **Packagist:**  | https://packagist.org/packages/jpmschuler/showpageeditors |
