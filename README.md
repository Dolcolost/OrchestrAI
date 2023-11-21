# OrchestrAI
 Final school project : an attempt to chart music

We use to chart music from youtube video. 
Via a website, the user input a youtube link. It start a job on azure which create a docker.
On docker the video is donwload in mp3 which is convert into a spectrogram. The model will then be called and generate a midi file.
The website receive the midi and convert it into chart and the user is able to download it.

We attempt to train multiple model from different papers :
Music Transcription Using Deep Learning : https://cs229.stanford.edu/proj2017/final-reports/5242716.pdf
Reinforcement Learning with Long Short-Term Memory https://proceedings.neurips.cc/paper/2001/file/a38b16173474ba8b1a95bcbc30d3b8a5-Paper.pdf

More details on the "Rapport PA" file (in french)
