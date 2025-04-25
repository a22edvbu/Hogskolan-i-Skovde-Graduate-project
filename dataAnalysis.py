import numpy as np
import pandas as pd
import matplotlib.pyplot as plt

sqlDataset = pd.read_csv('sqlData.csv')
mdbDataset = pd.read_csv('mdbData.csv')  

df1 = pd.DataFrame()
df2 = pd.DataFrame()
df1 = sqlDataset
df2 = mdbDataset

def standardMean():
    print (df1['Rows'].mean())
    print (df2['Rows'].std())

def lineDiagram1(): 
    plt.plot(df1['Rows'], label='MySQL')
    plt.plot(df2['Rows'], label='MongoDb')

    plt.title('Time to decrypt')
    plt.xlabel('Amount measured')
    plt.ylabel('Milliseconds processed')
    plt.legend()
    plt.show()
    
def lineDiagram2(): 
    plt.plot(df1['Decryption'], label='MySQL')
    plt.plot(df2['Decryption'], label='MongoDb')

    plt.title('Time to fetch rows')
    plt.xlabel('Amount measured')
    plt.ylabel('Milliseconds processed')
    plt.legend()
    plt.show()

lineDiagram1()
lineDiagram2()