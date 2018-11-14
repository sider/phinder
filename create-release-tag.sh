#!/bin/bash

version=$(./bin/phinder -v | cut -d' ' -f3)

if ! [[ $version =~ ^[0-9]\.[0-9]\.[0-9]$ ]]; then
  echo "Invalid version value found: $version" >&2
  exit 1
fi

git tag "v$version"
