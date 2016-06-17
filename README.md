# DrivingDistance
Problem Statement was:
1. Build a simple web app with one page where I can enter two locations. One is the origin, the other is the destination. The inputs should accept the full address or the lat long of a place. 
2. The goal is to find the driving time and distance between these two points and display it on the same page.
3. You will need to use Google Maps API to find this. Use this API key - AIzaSyB6ky0s6kmaxH15hsxsNHKuZeI6n_OG2eA

At any point in the future, if the same origin and destination are entered(for which we have already found the driving distance and time) either in address format or lat long format, we shouldn't 
use the Google Maps API again. We should store the results after every fetch and first try to reuse that for every new request and only use the Google Maps API if necessary.
