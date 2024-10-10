from docx import Document
import glob
import pyperclip

for i in glob.glob('*.docx', recursive=True):
    document = Document(i) #document to be pdf

    fulltext = " " #create list
    for paragraph in document.paragraphs:
        fulltext = fulltext + paragraph.text# + "\n"

    print(fulltext)
    pyperclip.copy(fulltext)


# parse through each document with the above code
# return the link to the document when found a word
# parse through the next document until finished