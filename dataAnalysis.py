import numpy as np
import pandas as pd
import matplotlib.pyplot as plt

sqlDataset = pd.read_csv('sqlData1.csv', header=None)
mdbDataset = pd.read_csv('mdbData1.csv', header=None)
sqlBrowserDataset = pd.read_csv('sqlBrowserDataTest.csv', header=None)
mdbBrowserDataset = pd.read_csv('mdbBrowserDataTest.csv', header=None)

sqlDataset.columns = ['ID', 'Decryption', 'Rows', "Table"]
mdbDataset.columns = ['ID', 'Decryption', 'Rows', "Table"]
sqlBrowserDataset.columns = ['Start', 'Stop', 'Diff']
mdbBrowserDataset.columns = ['Start', 'Stop', 'Diff']

df1 = pd.DataFrame();
df2 = pd.DataFrame();
df3 = pd.DataFrame();
df4 = pd.DataFrame();

df1 = sqlDataset
df2 = mdbDataset
df3 = sqlBrowserDataset
df4 = mdbBrowserDataset

#df1 = df1.sort_values(by='ID')
#df2 = df2.sort_values(by='ID')

print (df1, df2)


def standardMean():
    print(df1['Rows'].mean())
    print(df2['Rows'].std())

def lineDiagram1():                                     # Decryption
    plt.figure(figsize=(12,5))
    plt.plot(df1['Decryption'] * 1000, label='MySQL')
    plt.plot(df2['Decryption'] * 1000, label='MongoDb')
    
    plt.title('Dekrypteringstid')
    plt.xlabel('Antal mätpunkter')
    plt.ylabel('Responstid (ms)')
    plt.legend()
    plt.tight_layout()
    plt.show()

def lineDiagram2():                                     # Browser load time
    plt.figure(figsize=(12,5))
    
    plt.plot(df3['Diff'], label='MySQL')
    plt.plot(df4['Diff'], label='MongoDB')
    
    plt.title('Hämtningstider Browser (Firefox)')
    plt.xlabel('Amount measured')
    plt.ylabel('Responstid (ms)')
    plt.legend()
    plt.tight_layout()
    plt.show()

def lineDiagram3():                                     # Fetchtime
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
    y1 = df1['Decryption'] * 1000 
    y2 = df2['Decryption'] * 1000
    
    colors = ['tab:blue', 'tab:orange']
      
    x = ["MySQL", "MongoDB"]
    y = [y1.mean(), y2.mean()]
    
    plt.bar(x,y, color=colors)
    plt.title('Medelvärde Dekryption')
    plt.ylabel('Responstid (ms)')
    plt.legend()
    plt.tight_layout()
    plt.show()
    
def bars2():
    plt.figure(figsize=(12,5))
    y1 = df1['Table'] * 1000
    y2 = df2['Table'] * 1000
    
    colors = ['tab:blue', 'tab:orange']
      
    x = ["MySQL", "MongoDB"]
    y = [y1.mean(), y2.mean()]
    
    plt.bar(x,y, color=colors)
    plt.title('Medelvärde Fetch')
    plt.ylabel('Responstid (ms)')
    plt.legend()
    plt.tight_layout()
    plt.show()
    
def bars3():
    plt.figure(figsize=(12,5))
    y1 = df3['Diff']
    y2 = df4['Diff']
    
    colors = ['tab:blue', 'tab:orange']
      
    x = ["MySQL", "MongoDB"]
    y = [y1.mean(), y2.mean()]
    
    plt.bar(x,y, color=colors)
    plt.title('Medelvärde Laddningstid webbapplikation')
    plt.ylabel('Responstid')
    plt.legend()
    plt.tight_layout()
    plt.show()
    
#lineDiagram1()
lineDiagram2()
#lineDiagram3()
#bars1()
#bars2()
#bars3()
