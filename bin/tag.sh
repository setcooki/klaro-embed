#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
BASE_DIR="$(dirname "$DIR")"
VERSION=""
MESSAGE=""
UPDATE=0

for i in "$@"
do
case $i in
  --v=*)
  VERSION="${i#*=}"
  shift # past argument=value
  ;;
  --m=*)
  MESSAGE="${i#*=}"
  shift # past argument=value
  ;;
  --update)
  UPDATE=1
  shift # past argument=value
  ;;
  *)
  # unknown option
  ;;
esac
done;

if [[ -z "$VERSION" ]]; then
  echo "Need --v version number"
  exit 1;
fi;

sleep 2
if [[ $UPDATE -eq 1 ]]; then
  git commit -a -m "bump $VERSION"
  git push
  git tag -d v$VERSION
  git tag -a v$VERSION -m "bump $VERSION"
  git push origin v$VERSION -f
else
  git commit -a -m "create $VERSION"
  git push
  git tag -a v$VERSION -m "Version $VERSION $MESSAGE"
  git push --tags
fi;