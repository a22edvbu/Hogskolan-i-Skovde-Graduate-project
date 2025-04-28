import numpy as np
import pandas as pd
import matplotlib.pyplot as plt

sqlDataset = pd.read_csv('sqlData.csv', header=None)
mdbDataset = pd.read_csv('mdbData.csv', header=None)

sqlDataset.columns = ['ID', 'Decryption', 'Rows', 'Table']
mdbDataset.columns = ['ID', 'Decryption', 'Rows', 'Table']

df1 = pd.DataFrame();
df2 = pd.DataFrame();

df1 = sqlDataset
df2 = mdbDataset

#df1 = df1.sort_values(by='ID')
#df2 = df2.sort_values(by='ID')

print (df1, df2)


def standardMean():
    print(df1['Rows'].mean())
    print(df2['Rows'].std())

def lineDiagram1():
    plt.figure(figsize=(12,5))
    plt.plot(df1['Decryption'], label='MySQL')
    plt.plot(df2['Decryption'], label='MongoDb')
    
    plt.title('Dekrypteringstid')
    plt.xlabel('Antal mätpunkter')
    plt.ylabel('Responstid (ms)')
    plt.legend()
    plt.tight_layout()
    plt.show()

def lineDiagram2():
    plt.figure(figsize=(12,5))
    plt.plot(df1['Rows'], label='MySQL')
    plt.plot(df2['Rows'], label='MongoDb')
    
    plt.title('Radutskrift')
    plt.xlabel('Amount measured')
    plt.ylabel('Responstid (ms)')
    plt.legend()
    plt.tight_layout()
    plt.show()

def lineDiagram3():
    plt.figure(figsize=(12,5))
    
    plt.plot(df1['Table'] * 1000, label='MySQL')
    plt.plot(df2['Table'] * 1000, label='MongoDb')
    
    plt.title('Fetchtid')
    plt.xlabel('Antal fetches')
    plt.ylabel('Responstid (ms)')
    plt.legend()
    plt.tight_layout()
    plt.show()
    
def bars1():
    plt.figure(figsize=(12,5))
    y1 = df1['Decryption'].mean()
    y2 = df2['Decryption'].mean()
    
    colors = ['tab:blue', 'tab:orange']
      
    x = ["MySQL", "MongoDB"]
    y = [y1, y2]
    
    plt.bar(x,y, color=colors)
    plt.title('Medelvärde Dekryption')
    plt.ylabel('Responstid (ms)')
    plt.legend()
    plt.tight_layout()
    plt.show()
    
def bars2():
    plt.figure(figsize=(12,5))
    y1 = df1['Table'].mean()
    y2 = df2['Table'].mean()
    
    colors = ['tab:blue', 'tab:orange']
      
    x = ["MySQL", "MongoDB"]
    y = [y1, y2]
    
    plt.bar(x,y, color=colors)
    plt.title('Medelvärde Fetch')
    plt.ylabel('Responstid (ms)')
    plt.legend()
    plt.tight_layout()
    plt.show()
    
bars1()
bars2()
lineDiagram1()
lineDiagram3()
