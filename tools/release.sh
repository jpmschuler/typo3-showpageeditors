#!/usr/bin/env bash
git commit -a -m "[RELEASE] Releasing v$1";
git tag v$1  -m "[RELEASE] Releasing v$1";
git archive -o "showpageeditors_$1.zip" v$1;
