from flask import Flask, render_template
from markupsafe import escape
from docx import Document
import glob
import pyperclip


app = Flask(__name__)

@app.route('/')
@app.route('/index/')
def index():
    for i in glob.glob('AllSemmsFiles/*.docx', recursive=True):
        document = Document(i) #document to be pdf

    fulltext = " " #create list
    for paragraph in document.paragraphs:
        fulltext = fulltext + paragraph.text# + "\n"

    print(fulltext)
    pyperclip.copy(fulltext)
    return fulltext
# parse through each document with the above code
# return the link to the document when found a word
# parse through the next document until finished
