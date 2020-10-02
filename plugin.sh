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
#npm run build;

#Create tag
echo "Creating Tag";
git tag -a v"$versionNumber" -m "new version: $versionNumber";

#Push to GitHub
echo "Pushing to GitHub";
git push --tags origin;