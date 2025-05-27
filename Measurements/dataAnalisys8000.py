import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
import glob as glob
import os

pathSql = pd.read_csv("./Measurements/All Search/1500 Measures/8000 Limit/sqlQueryFetchALL8000Data1.csv",
    header=None,
    skiprows=1  # Skip the first row
)

pathMdb = pd.read_csv("./Measurements/All Search/1500 Measures/8000 Limit/mdbQueryFetchALL8000Data1.csv",
    header=None,
    skiprows=1  # Skip the first row
)

pathSqlInsert = pd.read_csv("./Measurements/Insert/8000 Limit/sqlInsertData1.csv",
    header=None,
    skiprows=1  # Skip the first row
)

pathMdbInsert = pd.read_csv("./Measurements/Insert/8000 Limit/mdbInsertData1.csv",
    header=None,
    skiprows=1  # Skip the first row
)

pathSql.columns = ['Table', 'Matches', 'AvgDecrypt']
pathMdb.columns = ['Table', 'Matches', 'AvgDecrypt']
pathSqlInsert.columns = ['Insert', 'Amount', 'AvgEncrypt', 'AvgInsert']
pathMdbInsert.columns = ['Insert', 'Amount', 'AvgEncrypt', 'AvgInsert']

df1 = pd.DataFrame();
df2 = pd.DataFrame();
df3 = pd.DataFrame();
df4 = pd.DataFrame();

df1 = pathSql
df2 = pathMdb
df3 = pathSqlInsert
df4 = pathMdbInsert

def standardMean(dataA, dataB):
    
    meanSql = dataA['Table'].mean()
    steSql = dataA['Table'].std()
    
    meanMdb = dataB['Table'].mean()
    steMdb = dataB['Table'].std()
    print("------------Medelvärde-------------")
    print(meanSql * 1000)
    print(meanMdb * 1000)
    print("--------------Standard Error-----------")
    print(steSql * 1000)
    print(steMdb * 1000)
    print("-------------------------")
    
def lineDiagramFetch():
    plt.figure(figsize=(12,5))
    
    plt.plot(df1['Table'] * 1000, label='MySQL')
    plt.plot(df2['Table'] * 1000, label='MongoDb')
    
    plt.title('Fetchtid 8000 rader')
    plt.xlabel('Antal fetches')
    plt.ylabel('Responstid (ms)')
    plt.legend()
    plt.tight_layout()
    plt.savefig("./Measurements/Fetch8000Line.png")
    plt.show()
    
def barsSteFetch():
    plt.figure(figsize=(6,5))
    
    y1 = df1['AvgDecrypt'] * 1000
    y2 = df2['AvgDecrypt'] * 1000
    
    y_means = [y1.mean(), y2.mean()]

    print(y_means)

    # Standard Error for each bar
    y_sems = [y1.std(ddof=1) / np.sqrt(len(y1)), y2.std(ddof=1) / np.sqrt(len(y2))]

    x_labels = ["MySQL", "MongoDB"]
    x_pos = np.arange(len(x_labels))
    colors = ['tab:blue', 'tab:orange']

    plt.bar(x_pos, y_means, yerr=y_sems, color=colors, capsize=10)
    plt.xticks(x_pos, x_labels)
    plt.title("Medelvärde Fetchtid 8000 rader")
    plt.ylabel("Responstid (ms)")
    plt.tight_layout()
    plt.savefig("./Measurements/Fetch8000Bars.png")
    standardMean(df1, df2)
    plt.show()

def lineDiagramDecrypt():
    plt.figure(figsize=(12,5))
    
    plt.plot(df1['AvgDecrypt'] * 1000, label='MySQL')
    plt.plot(df2['AvgDecrypt'] * 1000, label='MongoDb')
    
    plt.title('Dekrypteringstid 8000 rader')
    plt.xlabel('Antal fetches')
    plt.ylabel('Responstid (ms)')
    plt.legend()
    plt.tight_layout()
    plt.savefig("./Measurements/Decrypt8000Line.png")
    plt.show()
    
def barsSteDecrypt():
    plt.figure(figsize=(6,5))
    
    y1 = df1['AvgDecrypt'] * 1000
    y2 = df2['AvgDecrypt'] * 1000
    
    y_means = [y1.mean(), y2.mean()]

    print(y_means)

    # Standard Error for each bar
    y_sems = [y1.std(ddof=1) / np.sqrt(len(y1)), y2.std(ddof=1) / np.sqrt(len(y2))]

    x_labels = ["MySQL", "MongoDB"]
    x_pos = np.arange(len(x_labels))
    colors = ['tab:blue', 'tab:orange']

    plt.bar(x_pos, y_means, yerr=y_sems, color=colors, capsize=10)
    plt.xticks(x_pos, x_labels)
    plt.title("Medelvärde dekrypteringstid 8000 rader")
    plt.ylabel("Responstid (ms)")
    plt.tight_layout()
    plt.savefig("./Measurements/Decrypt8000Bars.png")
    plt.show()

def lineDiagramInsert():
    plt.figure(figsize=(12,5))
    
    plt.plot(df3['Insert'] * 1000, label='MySQL')
    plt.plot(df4['Insert'] * 1000, label='MongoDb')
    
    plt.title('Inserttid 8000 rader')
    plt.xlabel('Antal fetches')
    plt.ylabel('Responstid (ms)')
    plt.legend()
    plt.tight_layout()
    plt.savefig("./Measurements/Insert8000Line.png")
    plt.show()
    
def barsSteInsert():
    plt.figure(figsize=(6,5))
    
    y1 = df3['Insert'] * 1000
    y2 = df4['Insert'] * 1000
    
    y_means = [y1.mean(), y2.mean()]

    print(y_means)

    # Standard Error for each bar
    y_sems = [y1.std(ddof=1) / np.sqrt(len(y1)), y2.std(ddof=1) / np.sqrt(len(y2))]

    x_labels = ["MySQL", "MongoDB"]
    x_pos = np.arange(len(x_labels))
    colors = ['tab:blue', 'tab:orange']

    plt.bar(x_pos, y_means, yerr=y_sems, color=colors, capsize=10)
    plt.xticks(x_pos, x_labels)
    plt.title("Medelvärde inserttid 8000 rader")
    plt.ylabel("Responstid (ms)")
    plt.tight_layout()
    plt.savefig("./Measurements/Insert8000Bars.png")
    plt.show()

def lineDiagramEncrypt():
    plt.figure(figsize=(12,5))
    
    plt.plot(df3['AvgEncrypt'] * 1000, label='MySQL')
    plt.plot(df4['AvgEncrypt'] * 1000, label='MongoDb')
    
    plt.title('Krypteringstid 8000 rader')
    plt.xlabel('Antal fetches')
    plt.ylabel('Responstid (ms)')
    plt.legend()
    plt.tight_layout()
    plt.savefig("./Measurements/Encrypt8000Line.png")
    plt.show()
    
def barsSteEncrypt():
    plt.figure(figsize=(6,5))
    
    y1 = df3['AvgEncrypt'] * 1000
    y2 = df4['AvgEncrypt'] * 1000
    
    y_means = [y1.mean(), y2.mean()]

    print(y_means)

    # Standard Error for each bar
    y_sems = [y1.std(ddof=1) / np.sqrt(len(y1)), y2.std(ddof=1) / np.sqrt(len(y2))]

    x_labels = ["MySQL", "MongoDB"]
    x_pos = np.arange(len(x_labels))
    colors = ['tab:blue', 'tab:orange']

    plt.bar(x_pos, y_means, yerr=y_sems, color=colors, capsize=10)
    plt.xticks(x_pos, x_labels)
    plt.title("Medelvärde krypteringstid med 8000 rader")
    plt.ylabel("Responstid (ms)")
    plt.tight_layout()
    plt.savefig("./Measurements/Encrypt8000Bars.png")
    plt.show()
#lineDiagramFetch()
#barsSteFetch()
lineDiagramDecrypt()
barsSteDecrypt()
#lineDiagramInsert()
#barsSteInsert()
#lineDiagramEncrypt()
#barsSteEncrypt()

standardMean()