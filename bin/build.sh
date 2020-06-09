#!/bin/bash

if [ -z "$1" ]
  then
    echo "@todo"
else
  PKG_VERSION=$1
  PKG_NAME_PREFIX="LoremUserGenerator"
  PKG_NAME_SLUG="lorem-user-generator"
  PKGS_PATH="../packages"
  BUILDS_PATH="../build"
  NEW_PKG_NAME="$PKG_NAME_PREFIX-v$PKG_VERSION.zip"

  rm -rf $PKGS_PATH
  if [ ! -d $PKGS_PATH ]; then
    mkdir -p $PKGS_PATH
  fi

  mkdir -p "$PKGS_PATH/$PKG_NAME_SLUG"

  cp ../composer.phar "$PKGS_PATH/$PKG_NAME_SLUG/composer.phar"
  cp ../composer.json "$PKGS_PATH/$PKG_NAME_SLUG"
  cp ../composer.lock "$PKGS_PATH/$PKG_NAME_SLUG"
  cp ../lorem-user-generator.php "$PKGS_PATH/$PKG_NAME_SLUG"
  cp ../.editorconfig "$PKGS_PATH/$PKG_NAME_SLUG"
  cp -rf ../assets "$PKGS_PATH/$PKG_NAME_SLUG/assets"
  cp -rf ../src-2 "$PKGS_PATH/$PKG_NAME_SLUG/src-2"
  cp -rf ../test "$PKGS_PATH/$PKG_NAME_SLUG/test"
  cp -rf ../templates "$PKGS_PATH/$PKG_NAME_SLUG/templates"
  cp -rf ../templates "$PKGS_PATH/$PKG_NAME_SLUG/templates"

  cd "$PKGS_PATH/$PKG_NAME_SLUG" && ./composer.phar install && cd ..

  rm -rf $BUILDS_PATH
  if [ ! -d $BUILDS_PATH ]; then
    mkdir -p $BUILDS_PATH
  fi

  zip -q -r "$BUILDS_PATH/$NEW_PKG_NAME" "$PKGS_PATH/$PKG_NAME_SLUG"

  rm -rf $PKGS_PATH
fi
