echo "" &&
echo "======================================================" &&
echo "WordPress Plugin Builder for Better Sharing for WP" &&
echo "======================================================" &&
echo "" &&

#Ask & Store Version
echo "Version Number:  ";
read -r versionNumber;

#Remove Build Directory?
read -r -p "Remove plugin build directory after compression? y/n " removeDir;

buildDir='build'
mkdir -p "$buildDir"
buildLog="$buildDir/build-$versionNumber.txt"

echo "Building $versionNumber..."
echo "output will go to $buildLog"

# Create new branch
echo "Checking out build"
git checkout -b build-"$versionNumber" >> $buildLog 2>&1

# Run Build
echo "Building version $versionNumber";
npm run build >> $buildLog 2>&1;

#Create tag
echo "Creating Tag";
git tag -a v"$versionNumber" -m "new version: $versionNumber";

#Push to GitHub
echo "Pushing to GitHub";
git push --tags origin;

#Copy and move build files into new directory
STR="better-sharing-$versionNumber"
mkdir "$STR" >> $buildLog 2>&1

echo "Copying Admin Assets"
cp -r ./admin-assets "./$STR/admin-assets" >> $buildLog 2>&1

echo "Copying dist"
cp -r ./dist "./$STR/dist" >> $buildLog 2>&1

echo "Copying vendor dir"
cp -r ./vendor "./$STR/vendor" >> $buildLog 2>&1

echo "Copying includes"
cp -r ./includes "./$STR/includes" >> $buildLog 2>&1

echo "Copying public"
cp -r ./public "./$STR/public" >> $buildLog 2>&1

echo "Copying core files"
cp ./BetterSharingWP.php "./$STR/BetterSharingWP.php" >> $buildLog 2>&1

echo "Copying Readmes"
cp ./README.md "./$STR/README.md" >> $buildLog 2>&1
cp ./readme.txt "./$STR/readme.txt" >> $buildLog 2>&1

echo "Compressing"
zip -r "$STR.zip" "./$STR" >> $buildLog 2>&1

case $removeDir in
[Yy]* )
  echo "Cleanup..."
  rm -rf "./$STR";
  echo "Thank You"
;;
[Nn]* ) 
  echo "Thank You"
;;
esac