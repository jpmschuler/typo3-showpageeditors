#!/usr/bin/env bash
gsed -i "s/    'version' => .*/    'version' => '$1',/g" ext_emconf.php
git commit -a -m "[RELEASE] Releasing v$1";
git tag v$1  -m "[RELEASE] Releasing v$1";
git archive -o "showpageeditors_$1.zip" v$1;
