import numpy as np
import pandas as pd
import matplotlib.pyplot as plt

Limit = 1000

sqlDataset = pd.read_csv('./Measurements/Test/TEST-sqlQuery500Data1.csv', header=None)
mdbDataset = pd.read_csv('./Measurements/Test/TEST-mdbQuery500Data1.csv', header=None)

sqlFetchDataset = pd.read_csv('./Measurements/Filtered Search/500 Limit/sqlFilteredFetchALL500Data1.csv', header=None)
mdbFetchDataset = pd.read_csv('./Measurements/Filtered Search/500 Limit/mdbFilteredFetchALL500Data1.csv', header=None)

#sqlFetchAllDataset = pd.read_csv('./Measurements/All Search/1500 Measures/1000 Limit/sqlQueryFetchAll1000Data1.csv', header=None)
#mdbFetchAllDataset = pd.read_csv('./Measurements/All Search/1500 Measures/1000 Limit/mdbQueryFetchALL1000Data1.csv', header=None)

sqlFetchAllDataset500 = pd.read_csv('./Measurements/All Search/1500 Measures/500 Limit/sqlQueryFetchAll500Data1.csv',
    header=None,
    skiprows=1  # Skip the first row
)

mdbFetchAllDataset500 = pd.read_csv('./Measurements/All Search/1500 Measures/500 Limit/mdbQueryFetchALL500Data1.csv',
    header=None,
    skiprows=1  # Skip the first row
)

sqlFetchAllDataset1000 = pd.read_csv('./Measurements/All Search/1500 Measures/1000 Limit/sqlQueryFetchAll1000Data1.csv',
    header=None,
    skiprows=1  # Skip the first row
)

mdbFetchAllDataset1000 = pd.read_csv('./Measurements/All Search/1500 Measures/1000 Limit/mdbQueryFetchALL1000Data1.csv',
    header=None,
    skiprows=1  # Skip the first row
)

sqlFetchAllDataset2000 = pd.read_csv('./Measurements/All Search/1500 Measures/2000 Limit/sqlQueryFetchAll2000Data1.csv',
    header=None,
    skiprows=1  # Skip the first row
)

mdbFetchAllDataset2000 = pd.read_csv('./Measurements/All Search/1500 Measures/2000 Limit/mdbQueryFetchALL2000Data1.csv',
    header=None,
    skiprows=1  # Skip the first row
)

sqlFetchAllDataset4000 = pd.read_csv('./Measurements/All Search/1500 Measures/4000 Limit/sqlQueryFetchAll4000Data1.csv',
    header=None,
    skiprows=1  # Skip the first row
)

mdbFetchAllDataset4000 = pd.read_csv('./Measurements/All Search/1500 Measures/4000 Limit/mdbQueryFetchALL4000Data1.csv',
    header=None,
    skiprows=1  # Skip the first row
)

sqlFetchAllDataset = pd.read_csv(
    './Measurements/All Search/1500 Measures/500 Limit/sqlQueryFetchAll500Data1.csv',
    header=None,
    skiprows=1  # Skip the first row
)

mdbFetchAllDataset = pd.read_csv(
    './Measurements/All Search/1500 Measures/500 Limit/mdbQueryFetchALL500Data1.csv',
    header=None,
    skiprows=1  # Skip the first row
)


sqlBrowserDataset = pd.read_csv('sqlTestData.csv', header=None)
mdbBrowserDataset = pd.read_csv('mdbTestData.csv', header=None)

sqlDataset.columns = ['ID', 'Decryption', 'Rows']
mdbDataset.columns = ['ID', 'Decryption', 'Rows']
sqlFetchDataset.columns = ['Table', 'Matches', 'AvgDecrypt']
mdbFetchDataset.columns = ['Table', 'Matches', 'AvgDecrypt']
sqlFetchAllDataset.columns = ['Table', 'Matches', 'AvgDecrypt']
mdbFetchAllDataset.columns = ['Table', 'Matches', 'AvgDecrypt']
sqlBrowserDataset.columns = ['Start', 'Stop', 'Diff', 'SearchTerm']
mdbBrowserDataset.columns = ['Start', 'Stop', 'Diff', 'SearchTerm']

df1 = pd.DataFrame();
df2 = pd.DataFrame();
df3 = pd.DataFrame();
df4 = pd.DataFrame();
df5 = pd.DataFrame();
df6 = pd.DataFrame();

df7 = pd.DataFrame();
df8 = pd.DataFrame();
df9 = pd.DataFrame();
df10 = pd.DataFrame();
df11 = pd.DataFrame();
df12 = pd.DataFrame();
df13 = pd.DataFrame();
df14 = pd.DataFrame();
df15 = pd.DataFrame();
df16 = pd.DataFrame();

df1 = sqlDataset
df2 = mdbDataset
df3 = sqlBrowserDataset
df4 = mdbBrowserDataset
df5 = sqlFetchDataset
df6 = mdbFetchDataset

df7 = sqlFetchAllDataset
df8 = mdbFetchAllDataset

df9 = sqlFetchAllDataset500
df10 = mdbFetchAllDataset500

df11 = sqlFetchAllDataset1000
df12 = mdbFetchAllDataset1000

df13 = sqlFetchAllDataset2000
df14 = mdbFetchAllDataset2000

df15 = sqlFetchAllDataset4000
df16 = mdbFetchAllDataset4000

#df1 = df1.sort_values(by='ID')
#df2 = df2.sort_values(by='ID')

# print (df1, df2)

def standardMean():
    
    meanSql = df7['Table'].mean()
    steSql = df7['Table'].std()
    
    meanMdb = df8['Table'].mean()
    steMdb = df8['Table'].std()
    print("------------Medelvärde-------------")
    print(meanSql * 1000)
    print(meanMdb * 1000)
    print("--------------Standard Error-----------")
    print(steSql * 1000)
    print(steMdb * 1000)
    print("-------------------------")

def lineDiagram1():                                     # Decryption
    plt.figure(figsize=(12,5))
    plt.plot(df5['AvgDecrypt'] * 1000, label='MySQL')
    plt.plot(df6['AvgDecrypt'] * 1000, label='MongoDb')
    
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

def lineDiagramFiltered():                                     # Fetchtime
    plt.figure(figsize=(12,5))
    
    plt.plot(df5['Table'] * 1000, label='MySQL')
    plt.plot(df6['Table'] * 1000, label='MongoDb')
    
    plt.title('Fetchtid (8000 Limit)')
    plt.xlabel('Antal fetches')
    plt.ylabel('Responstid (ms)')
    plt.legend()
    plt.tight_layout()
    plt.show()

def lineDiagramAll():                                     # Fetchtime All Content
    plt.figure(figsize=(12,5))
    
    plt.plot(df7['Table'] * 1000, label='MySQL')
    plt.plot(df8['Table'] * 1000, label='MongoDb')
    
    plt.title('Fetchtid 8000 rader')
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
    
def barsFetchALL():
    plt.figure(figsize=(12,5))
    y1 = df7['Table']
    y2 = df8['Table']

    colors = ['tab:blue', 'tab:orange']
    
    x = ["MySQL", "MongoDB"]
    y = [y1.mean(), y2.mean()]

    title = "Medelvärde Fetchtid 1000 rader"
    plt.xticks([r + barWidth for r in range(len(bars1))], ['cond_A', 'cond_B', 'cond_C'])
    
    plt.bar(x,y, color=colors)
    plt.title(title)
    plt.ylabel('Responstid')
    plt.legend()
    plt.tight_layout()
    plt.savefig(title + " - Bar")
    plt.show()
    
def barsFetchALL_STE():
    plt.figure(figsize=(6,5))
    
    y1 = df7['Table'] * 1000
    y2 = df8['Table'] * 1000
    
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
    plt.show()
    
def barsFetchFiltered():
    plt.figure(figsize=(12,5))
    y1 = df5['Table']
    y2 = df6['Table']

    colors = ['tab:blue', 'tab:orange']
    
    x = ["MySQL", "MongoDB"]
    y = [y1.mean(), y2.mean()]

    plt.bar(x,y, color=colors)
    plt.title('Medelvärde Laddningstid webbapplikation')
    plt.ylabel('Responstid')
    plt.legend()
    plt.tight_layout()
    plt.show()
#lineDiagram1()   # Decryption Time
#lineDiagram2()   # Responsetime Browser
#lineDiagramFiltered()   # DB Fetch
lineDiagramAll()   # DB Fetch ALL
#bars1()
#bars2()
#bars3()
#barsFetchALL()
standardMean()
barsFetchALL_STE()
#barsFetchFiltered()


