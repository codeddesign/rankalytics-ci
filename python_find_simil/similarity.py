from __future__ import division
from goose import Goose
from difflib import SequenceMatcher
import re   
import os
import sys
import argparse
parser = argparse.ArgumentParser()


parser.add_argument("--domain", help="Domain to scan") 
parser.add_argument("--keyword", help="Target Keyword") 
parser.add_argument("--output", help="Output rankings file name") 



STOPWORDS = set([x.strip() for x in open(os.path.join(os.path.dirname(__file__),
        'stopwords')).read().split('\n')])






def main():
    args=parser.parse_args()
    url =args.domain
    g = Goose()
    article = g.extract(url=url)
    word_tuples=process_text(article.cleaned_text,1000) #(word, tf-idf rank)
    with open(args.output,'w') as output_file:
        for word,rank in word_tuples:  #word, tf-idf ranking, similarity score of given word and target keyword
            print >>output_file,word,rank,SequenceMatcher(None,args.keyword,word).ratio() 



def process_text(text, max_features=200, stopwords=None):
    
    if stopwords is None:
        stopwords = STOPWORDS
    
   # print stopwords

    d = {}
    for word in re.findall(r"\w[\w']*", text):
        word_lower = word.lower()
        if word_lower in stopwords:
            continue

        # Look in lowercase dict.
        if d.has_key(word_lower):
            d2 = d[word_lower]
        else:
            d2 = {}
            d[word_lower] = d2

        # Look in any case dict.
        if d2.has_key(word):
            d2[word] += 1
        else:
            d2[word] = 1

    d3 = {}
    for d2 in d.values():
        # Get the most popular case.
        first = sorted(d2.iteritems(), key=lambda x: x[1], reverse=True)[0][0]
        d3[first] = sum(d2.values())

    words = sorted(d3.iteritems(), key=lambda x: x[1], reverse=True)
    words = words[:max_features]
    maximum = float(max(d3.values()))
    for i, (word, count) in enumerate(words):
        words[i] = word, count/maximum
    
    return words



if __name__=="__main__":
   main()
