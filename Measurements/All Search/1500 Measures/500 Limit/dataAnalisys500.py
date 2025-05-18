import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
import glob as glob
import os

print("Current working directory:", os.getcwd())

pathSql = pd.read_csv("./Measurements/All Search/1500 Measures/500 Limit/sqlQueryFetchALL500Data1.csv",
    header=None,
    skiprows=1  # Skip the first row
)

pathMdb = pd.read_csv("./Measurements/All Search/1500 Measures/500 Limit/mdbQueryFetchALL500Data1.csv",
    header=None,
    skiprows=1  # Skip the first row
)

pathSql.columns = ['Table', 'Matches', 'AvgDecrypt']
pathMdb.columns = ['Table', 'Matches', 'AvgDecrypt']

df1 = pd.DataFrame();
df2 = pd.DataFrame();

df1 = pathSql
df2 = pathMdb

def standardMean():
    
    meanSql = df1['Table'].mean()
    steSql = df1['Table'].std()
    
    meanMdb = df2['Table'].mean()
    steMdb = df2['Table'].std()
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
    
    plt.title('Fetchtid 500 rader')
    plt.xlabel('Antal fetches')
    plt.ylabel('Responstid (ms)')
    plt.legend()
    plt.tight_layout()
    plt.savefig("fetch500Line.png")
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
    plt.title("Medelvärde Fetchtid 500 rader")
    plt.ylabel("Responstid (ms)")
    plt.tight_layout()
    plt.savefig("fetch500Bars.png")
    plt.show()

def lineDiagramDecrypt():
    plt.figure(figsize=(12,5))
    
    plt.plot(df1['AvgDecrypt'] * 1000, label='MySQL')
    plt.plot(df2['AvgDecrypt'] * 1000, label='MongoDb')
    
    plt.title('Decrypteringstid 500 rader')
    plt.xlabel('Antal fetches')
    plt.ylabel('Responstid (ms)')
    plt.legend()
    plt.tight_layout()
    plt.savefig("decrypt500Line.png")
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
    plt.title("Medelvärde decrypteringstid 500 rader")
    plt.ylabel("Responstid (ms)")
    plt.tight_layout()
    plt.savefig("decrypt500Bars.png")
    plt.show()

lineDiagramFetch()
barsSteFetch()
standardMean()
lineDiagramDecrypt()
barsSteDecrypt()