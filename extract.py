import pandas as pd
from dateutil import parser
import re

# Load the raw dataset
df = pd.read_csv('enronEmails.csv')  # Replace with your actual file name

# Prepare lists to hold the structured data
message_ids = []
dates = []
senders = []
recipients = []
subjects = []
bodies = []

for message in df['message']:
    # Message ID
    msg_id = re.search(r'Message-ID:\s*(.*)', message)
    message_ids.append(msg_id.group(1).strip() if msg_id else '')

    # Date
    # Replace the old date parsing code with this:
    date_match = re.search(r'Date:\s*(.*)', message)
    if date_match:
        raw_date = date_match.group(1).strip()
        try:
            parsed_date = parser.parse(raw_date)
            formatted_date = parsed_date.strftime('%Y-%m-%d')
        except Exception:
            formatted_date = ''  # Leave empty if parsing fails
    else:
        formatted_date = ''
    dates.append(formatted_date)


    # From
    from_match = re.search(r'From:\s*(.*)', message)
    senders.append(from_match.group(1).strip() if from_match else '')

    # To
    to_match = re.search(r'To:\s*(.*)', message)
    recipients.append(to_match.group(1).strip() if to_match else '')

    # Subject
    subject_match = re.search(r'Subject:\s*(.*)', message)
    subjects.append(subject_match.group(1).strip() if subject_match else '')

    # Body: starts after "X-FileName:" line
    body = ''
    if 'X-FileName:' in message:
        parts = message.split('X-FileName:', 1)
        if len(parts) > 1:
            body_lines = parts[1].splitlines()
            body_lines = body_lines[1:] if len(body_lines) > 1 else []  # Skip filename line
            body = "\n".join(body_lines).strip()
    bodies.append(body)
    
for i in range(len(message_ids)): 
    message_ids[i] = i + 1

# Create a clean DataFrame
clean_df = pd.DataFrame({
    'Message_ID': message_ids,
    'Date': dates,
    'From': senders,
    'To': recipients,
    'Subject': subjects,
    'Body': bodies
})

# Save to new CSV
clean_df.to_csv('structuredEmails.csv', index=False)
print("Cleaned data saved to 'structured_emails.csv'")
