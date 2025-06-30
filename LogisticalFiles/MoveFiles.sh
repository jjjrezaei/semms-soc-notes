#!/bin/bash

echo "enter word you want to search"
read search_term
echo "enter the directory you want it in"
read directory_insert

find . f -iname "*$search_term*" -exec mv {} Globalization_and_AmericanEmpire/ \;
echo "moving..."
