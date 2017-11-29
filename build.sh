#!/bin/bash

if [ -z "$1" ]
  then
    echo "[ERROR] You must pass the package version number as a parameter."$'\n'
else
  PKG_VERSION=$1
  PKG_NAME_PREFIX="LoremUserGenerator"
  PKG_NAME_SLUG="lorem-user-generator"
  PKGS_PATH="./packages"
  BUILDS_PATH="./build"
  NEW_PKG_NAME="$PKG_NAME_PREFIX-v$PKG_VERSION.zip"

  echo "Building the plugin..."
  gulp build

  echo "Looking for ./packages directory..."
  if [ ! -d $PKGS_PATH ]; then
    echo " Creating ./packages directory..."
    mkdir -p $PKGS_PATH
  else
    echo "Removing any \"$NEW_PKG_NAME\" file from packages directory..."
    rm -rf "$PKGS_PATH/$NEW_PKG_NAME"
  fi

  echo "Removing potentially nasty system files..."
  FILES_BACKLIST=(".DS_Store" ".AppleDouble" ".LSOverride" "Icon" "._*" ".DocumentRevisions-V100" ".fseventsd" ".Spotlight-V100" ".TemporaryItems" ".Trashes" ".VolumeIcon.icns" ".com.apple.timemachine.donotpresent" ".AppleDB" ".AppleDesktop" ".apdisk")
  for blackListedFileName in "${FILES_BACKLIST[@]}"
  do
    find "$BUILDS_PATH/$PKG_NAME_SLUG" -name "$blackListedFileName" -type f -delete
  done

  cd $BUILDS_PATH

  echo "Creating \"$NEW_PKG_NAME\" file..."
  zip -q -r ".$PKGS_PATH/$NEW_PKG_NAME" $PKG_NAME_SLUG

  echo "Done!"
fi
