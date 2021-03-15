echo "" &&
echo "======================================================" &&
echo "WordPress Plugin Builder for Better Sharing for WP" &&
echo "======================================================" &&
echo "" &&

me=`basename "$0"`

log() {
  echo "[$me] $1" | tee -a $buildLog
}

#Ask & Store Version
read -r -p "Version Number: " versionNumber

#Remove Build Directory?
read -r -p "Remove plugin build directory after compression? (y/N): " removeDir

# push tags to gh?
read -r -p "Push build tag to Github? (y/N): " prodBuild

buildDir='build'
mkdir -p "$buildDir"
buildLog="$buildDir/build-$versionNumber.txt"

echo "output will go to $buildLog"

log "Building $versionNumber..."

# Capture old branch
currentBranch=$(git branch --show-current)
log "Branching off $currentBranch"

# Create new branch
log "Creating a branch for the build"
git checkout -b build-"$versionNumber" >> $buildLog 2>&1

# Run composer
# we use it as a way to do PSR-4 namespacing
# remove the composer lock file, we don't require it because we don't have any external packages at this time.
log "Running composer"
composer install >> $buildLog 2>&1
rm composer.lock >> $buildLog 2>&1

# Run Build
log "Building version $versionNumber"
npm run build >> $buildLog 2>&1

# Tag, if this is a prod build
case $prodBuild in
[Yy]*)
  #Create tag
  log "Creating Tag"
  git tag -a v"$versionNumber" -m "new version: $versionNumber" >> $buildLog 2>&1

  log "Pushing to GitHub"
  git push --tags origin >> $buildLog 2>&1
  ;;
*) 
  log "Not tagging this release, you indicated that it was not a production build."
  ;;
esac

#Copy and move build files into new directory
STR="better-sharing-$versionNumber"
mkdir "$STR" >> $buildLog 2>&1

log "Copying Admin Assets"
cp -r ./admin-assets "./$STR/admin-assets" >> $buildLog 2>&1

log "Copying dist"
cp -r ./dist "./$STR/dist" >> $buildLog 2>&1

log "Copying vendor dir"
cp -r ./vendor "./$STR/vendor" >> $buildLog 2>&1

log "Copying includes"
cp -r ./includes "./$STR/includes" >> $buildLog 2>&1

log "Copying public"
cp -r ./public "./$STR/public" >> $buildLog 2>&1

log "Copying core files"
cp ./BetterSharingWP.php "./$STR/BetterSharingWP.php" >> $buildLog 2>&1

log "Copying Readmes"
cp ./README.md "./$STR/README.md" >> $buildLog 2>&1
cp ./readme.txt "./$STR/readme.txt" >> $buildLog 2>&1

log "Compressing"
zip -r "$STR.zip" "./$STR" >> $buildLog 2>&1

case $removeDir in
[Yy]* )
  log "Cleanup build folder..."
  rm -rf "./$STR" >> $buildLog 2>&1
;;
esac

# return to the previous branch
log "Returning to the previous branch"
git checkout $currentBranch >> $buildLog 2>&1

log "Thank You"

# we're good!
exit 0
