## get args
import argparse
from azure.storage.blob import BlobServiceClient, BlobClient, ContainerClient

import os
import imageio_ffmpeg
from moviepy.editor import *
import sys
import librosa
import librosa.display
import matplotlib.pyplot as plt
import numpy as np
import matplotlib.colors as mcolors
import io
from moviepy.video.io.bindings import mplfig_to_npimage
import keras
import pretty_midi

def loss_fn(y_true, y_pred):

    cross_entropy_loss = tf.keras.losses.categorical_crossentropy(y_true, y_pred)
    weighted_loss = tf.multiply(rewards, cross_entropy_loss)
    return tf.reduce_mean(weighted_loss)



# Create an argument parser
arguments = sys.argv
arguments = arguments[1:]

#args = parser.parse_args()
print(arguments[1])
link = arguments[1]
id_midi = arguments[3]

model = './saved_model'

os.mkdir("/app/mp3")
## download mp3 file

def download_audio(link, output_path):
    command = f'yt-dlp --extract-audio --audio-format mp3 -o "{output_path}/%(title)s.%(ext)s" {link}'
    os.system(command)
    listPath =os.listdir("/app/mp3")
    return "/app/mp3/"+listPath[0]

output_path = download_audio(link, '/app/mp3')
## Generate numpyArray from mp3

# Load audio file
y, sr = librosa.load(output_path)

# Set parameters
n_fft = 2048
hop_length = 512 #int(sr * 0.1)  # 0.01 seconds
n_mels = 128
fmax = 1000
frame_length = 2048
threshold_ratio = 0.1
min_db = -30 # set minimum dB threshold for noise removal

# Compute mel spectrogram of original signal
S_orig = librosa.feature.melspectrogram(y=y, sr=sr, n_fft=n_fft, hop_length=hop_length, n_mels=n_mels)

# Convert to decibel scale
D_orig = librosa.power_to_db(S_orig, ref=np.max)

# Compute short-time energy
energy = librosa.feature.rms(y=y, frame_length=frame_length, hop_length=hop_length)

# Compute threshold
threshold = np.max(energy) * threshold_ratio

# Find non-silent frames
non_silent_frames = np.where(energy > threshold)[1]

# Trim beginning of signal
start_frame = non_silent_frames[0]
y_trimmed_start = y[start_frame * hop_length:]

# Trim end of signal
end_frame = non_silent_frames[-1]
y_trimmed_end = y[:end_frame * hop_length + frame_length]

# Compute mel spectrogram of trimmed signal
S_trimmed = librosa.feature.melspectrogram(y=y_trimmed_end, sr=sr, n_fft=n_fft, hop_length=hop_length, n_mels=n_mels)

# Convert to decibel scale
D_trimmed = librosa.power_to_db(S_trimmed, ref=np.max)

# Remove noise below minimum dB threshold
D_trimmed[D_trimmed < min_db] = min_db

# Display spectrogram and waveforms
fig = plt.figure(figsize=(16, 1)) # adjust figsize to make the plot wider

librosa.display.specshow(D_trimmed, sr=sr, hop_length=hop_length, x_axis='time', y_axis='mel', fmax=fmax, cmap='gray')

plt.ylim([15, 258]) # adjust y-axis limits

plt.subplots_adjust(left=0, bottom=0, right=1, top=1, wspace=0, hspace=0)
frameon=False

plt.axis('off')
#plt.show()
print("graph ok")

numpy_fig = mplfig_to_npimage(fig)  # convert it to a numpy array

arr_gray = np.mean(numpy_fig, axis=2, keepdims=True) # gray scale numpy array


## delete mp3
os.remove(output_path)

## load model and array
model = keras.models.load_model(model, custom_objects={'loss_fn': loss_fn})
x_test = []
x_test.append(arr_gray.flatten())
x_test = np.array(x_test)

# Reshape x_train to have 3 dimensions
x_test = x_test.reshape( x_test.shape[0], -1, x_test.shape[1])

result = model.predict(x_test)
result = np.array(result)
result = np.maximum(result.round(3),0)

pitch_output = np.array(result[0][0][2::3], dtype='int')
pitch_output = np.minimum(pitch_output,127)
start_time_output = result[0][0][0::3]
end_time_output = result[0][0][1::3]

pm = pretty_midi.PrettyMIDI(initial_tempo=80)
velocity = 100
inst = pretty_midi.Instrument(program=0, is_drum=False, name='piano')
pm.instruments.append(inst)
for i in range(len(pitch_output)):

    inst.notes.append(pretty_midi.Note(velocity, pitch_output[i], start_time_output[i], end_time_output[i]))

pm.write( id_midi + '.mid')
name = id_midi + '.mid'
print("perdict ok")
#write into the blob
connection_string = "DefaultEndpointsProtocol=https;AccountName=xxx;AccountKey=xxx;EndpointSuffix=xxx"
blob_service_client = BlobServiceClient.from_connection_string(connection_string)

blob_client = blob_service_client.get_blob_client("midicontainer", blob= 'midi/'+name)

with open(name, "rb") as data:
    blob_client.upload_blob(data)

print("upload ok")
