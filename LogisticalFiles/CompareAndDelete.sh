#!/bin/bash
convertedFiles="~/programs/SemmsNotes/ConvertedDocFilesSemm"
oldFiles="~/programs/SemmsNotes/UnconvertedDocFilesSemm"

# Loop through each file in the first folder
for file1 in "$oldFiles"; do
    # Get the filename from the full path
	filename=$(basename "$file1")
    # Check if a file with the same name exists in the second folder
	if [ -f "$convertedFiles/$filename" ]; then
		echo "ERROR duplicate $filename found!"
		if [[ "$filename" == *.doc ]]; then
			rm "$filename"
			echo "$filename removed from $oldFiles !"
		fi
	else
		echo "$filename not found!"
	fi
done
