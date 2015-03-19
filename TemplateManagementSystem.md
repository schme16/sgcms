# Template Management System #

## How does it work? ##
sgCMS uses php's DOM Parser to read a templates index file and on-the-fly add all, inclusive media (css, js, images) as well as redirect images to the correct name-spaces.

## What does that mean for me? ##
Basically this means that all you as a template make needs to do is:
  1. Make an index (e.g index.php/index.htm/l) file
  1. Place all of your images into a folder named `'img'`
  1. Place all of your ECMAScripts (JavaScript/j-Script) into a folder named `'js'`
  1. Place all of your Cascading Style Sheets into a folder named `'css'`
  1. Edit the pre-made `'config.xml'` file
  1. Throw all that into a new folder in the template directory.

and Voila! You have yourself a new template.