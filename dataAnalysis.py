import numpy as np
import pandas as pd
import matplotlib.pyplot as plt

df1 = pd.read_csv('sqlDecryptData.csv', header=None)
df2 = pd.read_csv('mdbDecryptData.csv', header=None)

print(df1[1].to_string())  
print(df2[1].to_string())  

plt.plot(df1[1], label='MySQL')
plt.plot(df2[1], label='MongoDB')

plt.title('Time to decrypt')
plt.xlabel("Amount measured")
plt.ylabel("Milliseconds processed")
plt.legend()
plt.show()