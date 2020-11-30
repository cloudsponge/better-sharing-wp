echo "" &&
echo "======================================================" &&
echo "WordPress Plugin Builder for Better Sharing for WP" &&
echo "======================================================" &&
echo "" &&

#Ask & Store Version
echo "Version Number";
read -r versionNumber;


# Create new branch
git checkout -b build-"$versionNumber";

# Run Build
echo "Building version $versionNumber";
npm run build;

#Create tag
echo "Creating Tag";
git tag -a v"$versionNumber" -m "new version: $versionNumber";

#Push to GitHub
#echo "Pushing to GitHub";
#git push --tags origin;

#Copy and move build files into new directory
STR="build_$versionNumber" 
mkdir "$STR"

echo "Copying Admin Assets"
cp -r ./admin-assets "./$STR/admin-assets";

echo "Copying dist"
cp -r ./dist "./$STR/dist";

echo "Copying vendor dir"
cp -r ./vendor "./$STR/vendor";

echo "Copying includes"
cp -r ./includes "./$STR/includes";

echo "Copying public"
cp -r ./public "./$STR/public";

echo "Copying core files"
cp ./BetterSharingWP.php "./$STR/BetterSharingWP.php";

echo "Copying Readmes"
cp ./README.md "./$STR/README.md";
cp ./readme.txt "./$STR/readme.txt";