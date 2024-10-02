from docx import Document
from reportlab.pdfgen import canvas


document = Document("Sorrows of Empire.docx") #document to be pdf
#c = canvas.Canvas("Sorrows of Empire.pdf", pagesize=(595.27, 841.89))  # A4 pagesize

fulltext = " " #create list
for paragraph in document.paragraphs:
    #fulltext.append(paragraph.text) #append to fulltext list
    fulltext = fulltext + paragraph.text + "\n"
#    print(paragraph.text)

#"".join(str(x) for x in fulltext) #turn into a string
print(fulltext)


# parse through each document with the above code
# return the link to the document when found a word
# parse through the next document until finished