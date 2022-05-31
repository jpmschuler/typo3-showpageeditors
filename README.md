# EXT:showpageeditors
[![TYPO3 V9](https://img.shields.io/badge/TYPO3-9-orange.svg)](https://get.typo3.org/version/9)
[![TYPO3 V10](https://img.shields.io/badge/TYPO3-10-orange.svg)](https://get.typo3.org/version/10)
[![TYPO3 V11](https://img.shields.io/badge/TYPO3-11-orange.svg)](https://get.typo3.org/version/11)
[![TYPO3 V12](https://img.shields.io/badge/TYPO3-12-orange.svg)](https://get.typo3.org/version/12)

This TYPO3 extension provides a cli command to retrieve every editor which has a
page in the backend tree by retrieving users, groups and subgroups with access.

```sh
# just run the command "page:showVisibilityFor <pid>", e.g.
/vendor/bin/typo3cms page:showVisibilityFor 123
```
