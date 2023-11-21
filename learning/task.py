import pretty_midi
import requests
import time
from bs4 import BeautifulSoup
import subprocess
import argparse
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
import random
from keras.utils import pad_sequences
from azure.cli.core import get_default_cli

command = "service docker start"
os.system(command)

###### dl midi from musescore
USER_AGENTS = [
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36"
]
ind = random.randint(0, len(USER_AGENTS) - 1)
headers = {'User-Agent': USER_AGENTS[ind]}

def google_search(query):
    url = f"https://www.google.com/search?q=site%3Amusescore.com/user+{query}"
    headers = {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36"
    }
    
    try:
        response = requests.get(url, headers=headers)
        response.raise_for_status()
    
    except:
        return False
    
    
  
    

    soup = BeautifulSoup(response.text, "html.parser")
    search_results = soup.select(".g")

    for result in search_results:
        link = result.select_one("a")["href"]

        if "https://musescore.com/user/" in link:
            script = "cd /app/midi && node /app/node_modules/dl-librescore/dist/cli.js --input " + link + " --type midi"
            try:
                subprocess.run(script, shell=True, check=True)
                return True
            except subprocess.CalledProcessError as e:
                print("Erreur rencontrée :")
                print("Code de sortie :", e.returncode)
                print("Message d'erreur :", e.stderr)
                return False

    return False


def generator(file_list_numpy, file_list_midi, batch_size):
    #fill batch
    curIndex = 0
    batch_x = np.zeros((batch_size,160000))
    batch_y = np.zeros((batch_size,2502))
    while True:

        for i in range(batch_size):
            if file_list_midi[curIndex][-4:] != ".mid":
                continue
            #get X
            file_path = os.path.join("/app/numpy/", file_list_numpy[curIndex])
            x_train = np.load(file_path).flatten()


            #get Y
            midi_data = pretty_midi.PrettyMIDI("/app/midi/" + file_list_midi[curIndex])
            instr_notes = []
            y = []
            y_train = []

            for instr in midi_data.instruments:
                for note in instr.notes:
                    instr_notes.append((instr.name, pretty_midi.note_number_to_name(note.pitch), note))


            instr_notes.sort(key=lambda tup: (tup[2].start, tup[2].end))
            for j, note in enumerate(instr_notes):
                y.append(round(note[2].start, 3))
                y.append(round(note[2].end, 3))
                y.append(note[2].pitch)
            y_train.append(y)

            y_train = np.array(y_train)

            normalized_y_train = []
            padded_y_train = pad_sequences(y_train, 2502, padding='post', dtype='float')
            normalized_y_train = np.array(padded_y_train)

            batch_x[i] = x_train
            batch_y[i] = normalized_y_train[0]

            if i == len(file_list_numpy):
                i = 0

            if curIndex == len(file_list_numpy):
                curIndex = 0

            curIndex += 1


        yield batch_x.reshape( batch_size, -1, batch_x.shape[1]), batch_y.reshape( batch_size, -1, batch_y.shape[1])

def download_audio(link, output_path):
    command = f'yt-dlp --extract-audio --audio-format mp3 -o "{output_path}/%(title)s.%(ext)s" {link}'
    os.system(command)
    listPath =os.listdir("/app/mp3")
    return "/app/mp3/"+listPath[0]

os.mkdir("/app/midi")
os.mkdir("/app/numpy")
os.mkdir("/app/mp3")

# Create an argument parser
arguments = sys.argv
arguments = arguments[1:]
links = arguments[1]
links = links.split("@")

countValid = 0
for link in links:
    try:
        output_path = download_audio(link, '/app/mp3')
        filename = os.path.basename(output_path)
        name= filename[:-4]
        print("test mp4 passed")
    except:
        print("test mp4 wrong")
        continue

    # dl midi
    query = name.replace(" ", "+")
    if not google_search(query):
        print("query not valid")
        continue
    print("query valid")
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

    numpy_fig = mplfig_to_npimage(fig)  # convert it to a numpy array

    midi = np.mean(numpy_fig, axis=2, keepdims=True) # gray scale numpy array

    np.save(f"/app/numpy/{filename[:-4]}.npy", midi)

    ## delete mp4
    os.remove(output_path)

file_list_numpy = os.listdir("/app/numpy")
file_list_midi = os.listdir("/app/midi")

if len(file_list_numpy) == 0:
    exit(0)

model = keras.models.load_model('/app/saved_model')
model.compile(optimizer='adam', loss='categorical_crossentropy')
model.fit_generator(generator(file_list_numpy, file_list_midi, 1),steps_per_epoch=1, epochs=len(file_list_numpy))
model.save('/app/saved_model')

command = "az login --service-principal -u xxxx -p xxxx --tenant xxxx"
os.system(command)

command = "az acr build --image orchestraipredict --registry ochestrAIRegistry --file /app/predict/Dockerfile ."
os.system(command)

command = "az acr build --image orchestrailearning --registry ochestrAIRegistry --file /app/learning/Dockerfile ."
os.system(command)