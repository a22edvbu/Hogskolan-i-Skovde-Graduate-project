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

pathSqlInsert = pd.read_csv("./Measurements/Insert/500 Limit/sqlInsertData1.csv",
    header=None,
    skiprows=1  # Skip the first row
)

pathMdbInsert = pd.read_csv("./Measurements/Insert/500 Limit/mdbInsertData1.csv",
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

def standardMean():
    
    meanSql = df3['Insert'].mean()
    steSql = df3['Insert'].std()
    
    meanMdb = df4['Insert'].mean()
    steMdb = df4['Insert'].std()
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
    plt.savefig("./Measurements/Fetch500Line.png")
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
    plt.savefig("./Measurements/Fetch500Bars.png")
    plt.show()

def lineDiagramDecrypt():
    plt.figure(figsize=(12,5))
    
    plt.plot(df1['AvgDecrypt'] * 1000, label='MySQL')
    plt.plot(df2['AvgDecrypt'] * 1000, label='MongoDb')
    
    plt.title('Dekrypteringstid 500 rader')
    plt.xlabel('Antal fetches')
    plt.ylabel('Responstid (ms)')
    plt.legend()
    plt.tight_layout()
    plt.savefig("./Measurements/Decrypt500Line.png")
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
    plt.title("Medelvärde dekrypteringstid 500 rader")
    plt.ylabel("Responstid (ms)")
    plt.tight_layout()
    plt.savefig("./Measurements/Decrypt500Bars.png")
    plt.show()

def lineDiagramInsert():
    plt.figure(figsize=(12,5))
    
    plt.plot(df3['Insert'] * 1000, label='MySQL')
    plt.plot(df4['Insert'] * 1000, label='MongoDb')
    
    plt.title('Inserttid 500 rader')
    plt.xlabel('Antal fetches')
    plt.ylabel('Responstid (ms)')
    plt.legend()
    plt.tight_layout()
    plt.savefig("./Measurements/Insert500Line.png")
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
    plt.title("Medelvärde inserttid 500 rader")
    plt.ylabel("Responstid (ms)")
    plt.tight_layout()
    plt.savefig("./Measurements/Insert500Bars.png")
    plt.show()

def lineDiagramEncrypt():
    plt.figure(figsize=(12,5))
    
    plt.plot(df3['AvgEncrypt'] * 1000, label='MySQL')
    plt.plot(df4['AvgEncrypt'] * 1000, label='MongoDb')
    
    plt.title('Krypteringstid 500 rader')
    plt.xlabel('Antal fetches')
    plt.ylabel('Responstid (ms)')
    plt.legend()
    plt.tight_layout()
    plt.savefig("./Measurements/Encrypt500Line.png")
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
    plt.title("Medelvärde krypteringstid med 500 rader")
    plt.ylabel("Responstid (ms)")
    plt.tight_layout()
    plt.savefig("./Measurements/Encrypt500Bars.png")
    plt.show()
    

def lineDiagramInsert2():
    plt.figure(figsize=(12,5))
    
    y1 = df3['Insert'] - df3['AvgEncrypt']
    y2 = df4['Insert'] - df4['AvgEncrypt']
    
    y1 * 1000
    y2 * 1000
    
    plt.plot(y1, label='MySQL')
    plt.plot(y2, label='MongoDb')
    
    plt.title('Inserttid 500 rader (Utan krypteringstid)')
    plt.xlabel('Antal fetches')
    plt.ylabel('Responstid (ms)')
    plt.legend()
    plt.tight_layout()
    plt.savefig("./Measurements/InsertWithoutEncrypt500Line.png")
    plt.show()
    
def barsSteInsert2():
    plt.figure(figsize=(6,5))
    
    
    
    y1 = df3['AvgEncrypt'] 
    y2 = df4['AvgEncrypt'] 
    
    y_means = [y1.mean(), y2.mean()]
    
    # Pivot the data to have 'Product' as columns and 'Segment' as the index
    pivot_df = df.pivot(index='Segment',
                        columns='Product',
                        values='Amount_sold')

    # New dataframe with values on a 100% scale
    pivot_df_percentage = pivot_df.div(pivot_df.sum(axis=1), axis=0) * 100

    # Create a grouped barplot
    pivot_df_percentage.plot.bar(stacked=True,
                    grid=True)

    # Add a legend
    plt.legend(bbox_to_anchor=(1.04, 1), # shift the legend 4% on the right
            loc='upper left')

    # Display the plot
    plt.show()

    # print(y_means)

    # # Standard Error for each bar
    # y_sems = [y1.std(ddof=1) / np.sqrt(len(y1)), y2.std(ddof=1) / np.sqrt(len(y2))]

    # x_labels = ["MySQL", "MongoDB"]
    # x_pos = np.arange(len(x_labels))
    # colors = ['tab:blue', 'tab:orange']

    # plt.bar(x_pos, y_means, yerr=y_sems, color=colors, capsize=10)
    # plt.xticks(x_pos, x_labels)
    # plt.title("Medelvärde inserttid 500 rader (utan krypteringstid)")
    # plt.ylabel("Responstid (ms)")
    # plt.tight_layout()
    # plt.savefig("./Measurements/InsertWithoutEncrypt500Bars.png")
    # plt.show()
    
#lineDiagramFetch()
#barsSteFetch()
#standardMean()
#lineDiagramDecrypt()
#barsSteDecrypt()
#lineDiagramInsert()
#barsSteInsert()
lineDiagramInsert2()
barsSteInsert2()
#lineDiagramEncrypt()
#barsSteEncrypt()
